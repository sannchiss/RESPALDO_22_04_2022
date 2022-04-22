<?php

namespace App\Libs;
use DB;

class SqliteGenerator
{
	protected $DB;
	protected $routes;
	protected $DBpath;
	protected $vehicle;
	
	public function __construct($vehicle, $routes)
	{
		//se genera el archivo sqlite
		$this->vehicle = $vehicle;
		$date = date('Ymd');
		$time = date('hms');
		$path = storage_path("app/public/sqlite_db/{$date}");
		if(!file_exists($path)){
			mkdir($path,0775,true);
		}
		$sqliteDB = "{$path}/{$vehicle}_{$time}.sqlite";
		if(file_exists($sqliteDB)){
			unlink($sqliteDB);
		}
		touch($sqliteDB);
		$this->DBpath = "sqlite_db/{$date}/{$vehicle}_{$time}.sqlite.gz";
		$this->routes = $routes;
		$this->DB = new \SQLite3($sqliteDB);
		$this->createSchema();
		$this->insertData();
		$this->close();

		$this->gzCompressFile($sqliteDB);
	}

	public function getDownloadUrl(){
		return asset("storage/{$this->DBpath}");
	}

	protected function createSchema(){

		$this->DB->exec("CREATE TABLE statuses (
			id INTEGER PRIMARY KEY,
			code TEXT,
			label TEXT,
			system_area_id INTEGER,
			system_area_code TEXT
		)");

		$this->DB->exec("CREATE TABLE status_reasons (
			id INTEGER PRIMARY KEY,
			label TEXT,
			status_id INTEGER,
			status_code TEXT
		)");

		$this->DB->exec("CREATE TABLE documents (
			id INTEGER PRIMARY KEY,
			route_id INTEGER, 
			route_code TEXT,
			code TEXT,
			customer_id INTEGER,
			customer_code TEXT,
			customer_rut TEXT,
			packages INTEGER,
			row_order TEXT
		)");

		$this->DB->exec("CREATE TABLE document_details (
			id INTEGER PRIMARY KEY,
			document_id INTEGER, 
			document_code TEXT,
			product_code TEXT,
			product_label INTEGER,
			quantity DOUBLE
		)");

		$this->DB->exec("CREATE TABLE customers (
			id INTEGER PRIMARY KEY,
			rut TEXT,
			name TEXT,
			address TEXT,
			commune TEXT,
			lat REAL,
			lon REAL
		)");
	}
	protected function insertData(){

		/* Insert Statuses */
		$statuses = DB::select("SELECT statuses.id, statuses.code, 
			CASE statuses.code 
				WHEN 'ACCEPTED' THEN 'Entrega total'
				WHEN 'REJECTED' THEN 'Rechazo total'
				WHEN 'PARTIAL_REJECTION' THEN 'Entrega parcial'
				WHEN 'REDESPACHING' THEN 'Redespacho'
				ELSE statuses.label
			END AS label, system_areas.id AS system_area_id, system_areas.code AS system_area_code  
			FROM statuses 
			JOIN system_areas ON system_areas.id = statuses.system_area_id 
				AND system_areas.code IN ('DOCUMENT', 'DOCUMENT_DETAIL')
			WHERE statuses.code IN('ACCEPTED', 'REJECTED', 'PARTIAL_REJECTION', 'REDESPACHING')
		");
		$fields = ['id' => 'int', 'code' => 'text', 'label' => 'text', 'system_area_id' => 'int', 'system_area_code' => 'text'];
		$this->executeInsert('statuses', $fields, $statuses);

		//RAZONES DE ESTATUS
		$status_reasons = DB::select("SELECT status_reasons.id, status_reasons.label, statuses.id AS status_id, statuses.code AS status_code
			FROM status_reasons 
			JOIN statuses ON statuses.id = status_reasons.status_id
			JOIN system_areas ON system_areas.id = statuses.system_area_id 
				AND system_areas.code IN ('DOCUMENT', 'DOCUMENT_DETAIL')
		");

		$fields = ['id' => 'int', 'label' => 'text', 'status_id'  => 'int', 'status_code'  => 'text'];
		$this->executeInsert('status_reasons', $fields, $status_reasons);

		//CLIENTES
		$customers = DB::select("SELECT 
			customer_branches.id, customer_branches.rut, customer_branches.label AS name, customer_branches.address, communes.label AS commune,customer_branches.lat, customer_branches.lon
			FROM customer_branches
			JOIN communes ON communes.id = customer_branches.commune_id"
		);

		$fields = ['id' => 'int', 'rut' => 'text', 'name' => 'text', 'address'  => 'text', 'commune'  => 'text', 'lat'  => 'real', 'lon'  => 'real'];
		$this->executeInsert('customers', $fields, $customers);

		$routes = implode(",", $this->routes) ?? 'NOEXIST';


		//DOCUMENTOS
		$documents = DB::select("SELECT 
			documents.id, documents.route_id, routes.code AS route_code, documents.code, documents.customer_branch_id AS customer_id, customer_branches.code AS customer_code, customer_branches.rut AS customer_rut, documents.packages, documents.row_order
			FROM documents
			JOIN statuses ON statuses.id = documents.status_id
			JOIN routes ON routes.id = documents.route_id AND routes.code IN ({$routes})
			JOIN vehicles  ON vehicles.code = '{$this->vehicle}'
                            AND vehicles.id = routes.vehicle_id
			JOIN customer_branches ON customer_branches.id = documents.customer_branch_id
			WHERE statuses.code IN ('IN_DELIVERY', 'PENDING_DEPARTURE') "
		);
		$fields = ['id' => 'int', 'route_id' => 'int', 'route_code' => 'text', 'code'  => 'text', 'customer_id'  => 'int', 'customer_code' => 'text', 'customer_rut' => 'text', 'packages'  => 'int', 'row_order' => 'text'];
		$this->executeInsert('documents', $fields, $documents);

		//DETALLES DE DOCUMENTOS
		$document_details = DB::select("SELECT 
			document_details.id, document_details.document_id, documents.code AS document_code, products.code AS product_code, products.label AS product_label, document_details.quantity
			FROM documents
			JOIN statuses ON statuses.id = documents.status_id
			JOIN routes ON routes.id = documents.route_id AND routes.code IN ({$routes})
			JOIN vehicles  ON vehicles.code = '{$this->vehicle}'
                            AND vehicles.id = routes.vehicle_id
			JOIN document_details ON document_details.document_id = documents.id
			JOIN products ON products.id = document_details.product_id
			WHERE statuses.code IN ('IN_DELIVERY', 'PENDING_DEPARTURE')"
		);
		$fields = ['id' => 'int', 'document_id' => 'int', 'document_code' => 'text', 'product_code'  => 'text', 'product_label'  => 'text', 'quantity'  => 'double'];
		$this->executeInsert('document_details', $fields, $document_details);

	}

	protected function executeInsert($table,$fields,$datas){
		$fieldsKeys = array_keys($fields);
		$fieldsString = implode(",", $fieldsKeys);
		$lastField = array_last($fieldsKeys);

		$this->DB->exec('BEGIN;');
		foreach ($datas as $data) {
			$sql = "INSERT INTO {$table} ({$fieldsString}) VALUES (";
			//tomo los valores de la consulta de postgres y 
			//se pasan al sql contruyendo un string sql y ejecutandolo
			foreach ($fields as $key => $field) {

				$fieldValue =  $data->{$key} ?? 'NULL';
				if($field == 'int' || $field == 'real' || $fieldValue == 'NULL' ){
					$sql .= " ".$fieldValue." ";
				}else{
					$sql .= " '".$data->{$key}."'";
				}

				if($key != $lastField){
					$sql .= ",";
				} else {
					$sql .= ");";
				}
			}
			//ejecuta el sql 
			$toEx = $this->DB->prepare($sql);
			$toEx->execute();
		}
		$this->DB->exec('COMMIT;');
	}
	protected function close()
	{
		$this->DB->close();
	}

	protected function gzCompressFile($source, $level = 9){ 
	    $dest = $source . '.gz'; 
	    $mode = 'wb' . $level; 
	    $error = false; 
	    if ($fp_out = gzopen($dest, $mode)) { 
	        if ($fp_in = fopen($source,'rb')) { 
	            while (!feof($fp_in)) 
	                gzwrite($fp_out, fread($fp_in, 1024 * 512)); 
	            fclose($fp_in); 
	        } else {
	            $error = true; 
	        }
	        gzclose($fp_out); 
	    } else {
	        $error = true; 
	    }
	    if ($error)
	        return false; 
	    else
	        return $dest; 
	} 

}