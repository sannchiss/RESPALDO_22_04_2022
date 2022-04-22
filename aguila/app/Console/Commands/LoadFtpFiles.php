<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
//use Log;
use Monolog\Logger;
use Monolog\Logger as Monolog;
use Monolog\Handler\StreamHandler;

class LoadFtpFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:ftp_files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load ftp files to sistem';

    private $monolog;

    public function __construct()
    {
        parent::__construct();
        $date = date('Y-m-d');
        $monolog = new Logger(env('APP_ENV'));
        
       // $this->monolog = Log::getMonolog();
        $monolog->pushHandler(new StreamHandler(storage_path("logs/ftp_files/Upload-{$date}.log"), Monolog::INFO));
        $this->monolog = $monolog;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ///$selectedDisk = $this->argument('disk');
        $disk = Storage::disk( env('DISK_BRIDGE') );
        $fileParams = \Config::get('files_read.document');
        
        try {
            $files = $disk->allFiles( env('FTP_GET') );
            $isError = false;
        } catch (\Exception $e) {
            $this->monolog->info("Host FTP no responde");
            $isError = true;
        }

        if($isError){
            return;
        }

        foreach ($files as $file) {
            //separo carpeta de nombre Ej. qaportal/prisa/20180831083310.csv
            $fileName = explode('/', $file);
            $fileName = $fileName[count($fileName)-1];

            $localFile = $this->copyFile($file, $fileName, $disk); //copio archivo
            $data = $this->loadFile($fileName, $localFile, $fileParams); //leo y valido la data
            if( count($data['inserts']) >= 1){
                $rutas = $this->Store($data, $file, $fileName); //Guardo en BD
                $this->sendData($rutas);
            }else{
                $this->monolog->info("sin datos archivo: {$file} del FTP");
            }
            $this->monolog->info("se elimina archivo: {$file} del FTP");
            $disk->delete($file);
            
        }
    }
    private function sendData($rutas){
        foreach ($rutas as $ruta) {
            $request   = new Request($ruta);
            $this->monolog->info("Enviando ruta {$ruta['routeNumber']}");
            $result = app('App\Http\Controllers\Api\DocumentController')->store($request);
            \Log::info("DATA SEND RESULT", [$result]);
        }
    }

    private function copyFile($file, $fileName, $disk){
        // Retrieve a read-stream
        $stream = $disk->readStream($file);
        $textStream = iconv("ISO-8859-14", "UTF-8//TRANSLIT", stream_get_contents($stream));
        $contents = str_replace('"','',$textStream);
        fclose($stream);
        // Create or overwrite using a stream.
        $putStream = tmpfile();
        fwrite($putStream, $contents);
        rewind($putStream);

        $newName = date("His").$fileName;
        $date = date('Ymd');
        $newFilePath = "upload/{$date}/{$newName}";
        Storage::disk('local')->putStream($newFilePath, $putStream);
        if (is_resource($putStream)) {
            fclose($putStream);
            return $newFilePath;
        }

    }

    private function loadFile($fileName, $localFile, $fileParams){

        $this->monolog->info("Leyendo archivo file -> {$fileName}"); 
        $path = storage_path('app')."/{$localFile}"; 
        $rows = 0;
        $errors = [];
        $inserts = [];
        if (($gestor = fopen($path, "r")) !== FALSE) {
            while (($datos = fgetcsv($gestor, 0, ",")) !== FALSE) {
                $rows++;
                $datos = array_map('trim', $datos);
                #$this->monolog->info("Leyendo linea ->{$rows}", $datos); 
                if($rows == 1){ //estoeselheader
                    continue;
                }
                $columns = count($datos);
                if($columns < $fileParams['columns'] || $columns > $fileParams['columns'] ){
                    $numRealColumns = $columns < $fileParams['columns'] ? 'inferior' : 'superior';
                    $errors[] = [
                        'linea'  => $rows,
                        'column'  => null,
                        'desc'   => "número de columnas {$numRealColumns} al esperado"
                    ];
                    $this->monolog->info("Error linea ->{$rows}", end($errors));
                    continue;
                }

                for ($c=0; $c < $columns; $c++) {
                    if(!$this->isValidType($fileParams['type'][$c], $datos[$c])){
                        $errors[] = [
                            'linea'  => $rows,
                            'column' => $c+1,
                            'desc'   => "tipo de dato ({$datos[$c]}) erroneo se esperaba {$fileParams['type'][$c]}",
                        ];
                        $this->monolog->info("Error linea ->{$rows}", end($errors));
                        continue 2;
                    }
                }

                $inserts[] = array_combine($fileParams['heads'], $datos);

            }
            fclose($gestor);
        }
        return [ 'errors' => $errors, 'inserts' => $inserts];
    }

    private function get_numeric($val) { 
        if (is_numeric($val)) { 
            return $val + 0; 
        } 
        return 'no'; 
    } 

    private function isValidType($type, $dato){
        switch ($type) {
            case 'string':
                return true;
                break;

            case 'integer':
                if(is_integer($this->get_numeric($dato))){
                    return true;
                }
                break;

            case 'double':
                if(is_numeric($dato)){
                    return true;
                }
                break;
            case 'stringBoolean':
                if(trim($dato) == 'S' || trim($dato) == 'N'){
                    return true;
                }
                break;
            default:
                return false;
                break;
        }
        return false;
    }

    private function Store($data, $file,$fileName){
        $this->monolog->info("Convirtiendo CSV a JSON  -> {$fileName}");
        $count = count($data['inserts']);
        $this->monolog->info("Cantidad esperada a convertir {$count}  file -> {$fileName}");
        $i = 0;
        $rutas = [];
        foreach ($data['inserts'] as  $row) {
            //$this->monolog->info("Guardando registro -> {$file['name']}", $insert);
            ///$row = $file['update']($insert);
            try{
                if( !isset( $rutas[$row['routeNumber']] )){
                    $rutas[$row['routeNumber']] = [
                        "company"      => trim($row['company']),
                        "routeNumber"  => trim($row['routeNumber']),
                        "routeDate"    => trim($row['routeDate']),
                        "movil"        => trim($row['movil']),
                        "plate"        => trim($row['plate']),
                        "codDriver"    => trim($row['codDriver']),
                        "namDriver"    => trim($row['namDriver']),
                        "codAux"       => trim($row['codAux']),
                        "namAux"       => trim($row['namAux']),
                        "codCarriage"  => trim($row['codCarriage']),
                        "compCarriage" => trim($row['compCarriage']),
                        "order"        => [
                            [
                                "cRut"        => trim($row['cRut']),
                                "cCCost"      => trim($row['cCCost']),
                                "cName"       => trim($row['cName']),
                                "cDir"        => trim($row['cDir']),
                                "cCommune"    => trim($row['cCommune']),
                                "cPhone"      => trim($row['cPhone']),
                                "cX"          => trim($row['cX']),
                                "cY"          => trim($row['cY']),
                                "cRegion"     => trim($row['cRegion']),
                                "codSalesMan" => trim($row['codSalesMan']),
                                "namSalesMan" => trim($row['namSalesMan']),
                                "email"       => trim($row['email']),
                                "orderNumber" => trim($row['orderNumber']),
                                "guideNumber" => trim($row['guideNumber']),
                                "isDelivery"  => 1,
                                "guideDate"   => trim($row['guideDate']),
                                "rowOrder"    => trim($row['rowOrder']),
                                "orderBulk"   => trim($row['orderBulk']),
                                "items" => [
                                    [
                                        "codItem"  => trim($row['codItem']),
                                        "desItem"  => trim($row['desItem']),
                                        "cantItem" => trim($row['cantItem']),
                                        "itemLine" => trim($row['itemLine']),
                                    ]
                                ]
                            ]
                        ],
                    ];
                    continue;
                }

                $exist = false;
                $ordenes = $rutas[$row['routeNumber']]['order'];
                foreach ($ordenes as $key => $orden) {
                    //si existe la orden creo solo ITEM
                    if($orden['guideNumber'] == trim($row['guideNumber'])){
                        $rutas[$row['routeNumber']]['order'][$key]['items'][] = [
                                    "codItem"  => trim($row['codItem']),
                                    "desItem"  => trim($row['desItem']),
                                    "cantItem" => trim($row['cantItem']),
                                    "itemLine" => trim($row['itemLine']),
                                ];
                        $exist = true;
                        break;
                    }
                }

                if($exist == false){
                    $rutas[$row['routeNumber']]['order'][] = [
                        "cRut"        => trim($row['cRut']),
                        "cCCost"      => trim($row['cCCost']),
                        "cName"       => trim($row['cName']),
                        "cDir"        => trim($row['cDir']),
                        "cCommune"    => trim($row['cCommune']),
                        "cPhone"      => trim($row['cPhone']),
                        "cX"          => trim($row['cX']),
                        "cY"          => trim($row['cY']),
                        "cRegion"     => trim($row['cRegion']),
                        "codSalesMan" => trim($row['codSalesMan']),
                        "namSalesMan" => trim($row['namSalesMan']),
                        "email"       => trim($row['email']),
                        "orderNumber" => trim($row['orderNumber']),
                        "guideNumber" => trim($row['guideNumber']),
                        "isDelivery"  => 1,
                        "guideDate"   => trim($row['guideDate']),
                        "rowOrder"    => trim($row['rowOrder']),
                        "orderBulk"   => trim($row['orderBulk']),
                        "items" => [
                            [
                                "codItem"  => trim($row['codItem']),
                                "desItem"  => trim($row['desItem']),
                                "cantItem" => trim($row['cantItem']),
                                "itemLine" => trim($row['itemLine']),
                            ]
                        ]
                    ];
                }
            }
            catch(\Exception $e){
                $this->monolog->info("Error convertir a json registro en {$fileName}", $row);
                $this->monolog->info($e->getMessage());
            }
        }

        $this->monolog->info("Cantidad convertida {$count} file -> {$fileName}");
        $this->monolog->info("Fin de conversion json de archivo {$fileName}");
        return $rutas;

    }
}