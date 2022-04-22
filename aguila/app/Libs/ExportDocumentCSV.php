<?php

namespace App\Libs;
use DB;
use Illuminate\Support\Facades\Log;
use App\Models\Cellphone;
use App\Models\EmployeeType;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Models\Document;
use App\Models\DocumentDetail;
use App\Models\DocumentAttachment;
use App\Models\Route;
use App\Models\Status;

class ExportDocumentCSV
{

    public function generate( $doc_id )
    {
        $head = '' ;
        $data_doc = '';

        Log::info('Generando CSV para documento id ['. $doc_id .']');
	
        $doc_obj = Document::find( $doc_id );
        if ( empty( $doc_obj ) )
        {
            Log::info('Objeto de documento no encontrado.');
            return;
        };

        $factory_obj = DB::select("select factory.code as code from factory join document on factory.id = document.id_factory where document.id = '". $doc_id ."'");

        //Si la empresa es de prilogic le cambio el path a donde guarda 
        //creo una carpeta extra solo para prilogic
        //para llevar orden 
        if($factory_obj[0]->code <= 999 && env('NEW_FLUJO_PRILOGIC') == true){
            $path_to_save = env('PRILOGIC_CSV_PATH');
            $path_to_save .= date('Ym')."/";
            $path_to_save .= date('d')."/";
            $path_to_save .= $doc_obj->document."/";
            $docNumber = $doc_obj->document;
            mkdir($path_to_save,0770, true);
        }else{
            $path_to_save = env('DOWNLOAD_CSV_PATH');
        }
        

        Log::info('Generando CSV para documento id ['. $path_to_save .']');
        # Se procede a generar los datos del cierre de documento en un archivo
        # .cvs para posteriormente ser publicado por ftp hacia el cliente.
        $csvFileName = date('YmdHis') .'_'. $doc_obj->document .'_'. $factory_obj[0]->code.'.csv';
        $Filename = $path_to_save.$csvFileName;
                    
        $output = fopen($Filename, 'w');

        $status_obj = DocumentStatus::find( $doc_obj->id_status );
        if ( empty( $status_obj ) )
        {
            Log::info('Objeto de Status no encontrado.');
        };

        $customer_obj = Customer::find( $doc_obj->id_client  );
        if ( empty( $customer_obj ) )
        {
            Log::info('Objeto de Customer no encontrado.');
        };

        $doc_employee_obj = DB::select("select employee.code as code from document_employee join employee on document_employee.id_employee = employee.id where employee.type_employee = 0 and document_employee.id_document = '". $doc_id ."'");
        if ( empty( $doc_employee_obj ) )
        {
            Log::info('Objeto de Employee no encontrado.');
        };

        $doc_employee2_obj = DB::select("select employee.code as code from document_employee join employee on document_employee.id_employee = employee.id where employee.type_employee = 1 and document_employee.id_document = '". $doc_id ."'");
        if ( empty( $doc_employee2_obj ) )
        {
            Log::info('Objeto de Employee2 no encontrado.');
        };


        # Se define la cabezera a imprimir .
        $DOC_HEAD = array (
                'empresa',
                'uploaded_date',
                'delivery_date',
                'document',
                'status',
                'status_des',
                'plate_number',
                'employee',
                'employee2',
                'dispatch_quantity',
                'dispatch_bulk',
                'dispatch_volume',
                'rejected_quantity',
                'rejected_bulk',
                'rejected_volume',
                'customer',
                'customer_code',
                'customer_subcode',
                'latitude',
                'longitude',
                'route_sheet',
                'order_number',
                'modified',
                'recibe',
                'other'
                );

        foreach ( $DOC_HEAD as $field )
        {
            switch ( $field )
            {
            case "empresa":
                $data_doc .= $factory_obj[0]->code. ',';
                break;
            case "status":
                $data_doc .= $status_obj->label. ',';
                break;
            case "status_des":
                $data_doc .= $status_obj->description. ',';
                break;
            case "customer":
                $data_doc .= $customer_obj->name . ',';
                break;
            case "customer_code":
                $data_doc .= $customer_obj->code . ',';
                break;
            case "customer_subcode":
                $data_doc .= $customer_obj->subcode . ',';
                break;
            case "employee":
                $data_doc .= (!empty($doc_employee_obj[0]->code) ? $doc_employee_obj[0]->code : '') . ',';
                break;
            case "employee2":
                $data_doc .= (!empty($doc_employee2_obj[0]->code) ? $doc_employee2_obj[0]->code : '') . ',';
                break;
            default:
                $data_doc .= $doc_obj->$field . ',';
                break;
            }
        } ;

        $data_doc = substr($data_doc, 0, -1);

        # Se procede a buscar la informacion de los productos asociados.
        # Se define la cabezera para los items
        $ITEM_HEAD = array (
            'product',
            'code',
            'status',
            'status_des',
            'dispatch_quantity',
            'dispatch_bulk',
            'dispatch_volume',
            'rejected_quantity',
            'rejected_bulk',
            'rejected_volume',
            'line'
        );

        fputcsv($output,  array_merge( $DOC_HEAD, $ITEM_HEAD) );

        $documentItems = DocumentItem::where('id_document',$doc_obj->id)
                        ->get();

        $line = '';

        foreach ($documentItems as $item)
        {

            $product_item_obj  = Product::find( $item->id_product );
            $status_item_obj   = DocumentStatus::find( $item->id_status );

            $doc_arr = explode( ',',$data_doc );

            $item_arr = array(
                $product_item_obj->description, // 'product'
                $product_item_obj->code,        // 'code'
                $status_item_obj->label,        // 'status'
                $status_item_obj->description,  // 'status_des'
                $item->dispatch_quantity,       // 'dispatch_quantity'
                $item->dispatch_bulk,           // 'dispatch_bulk'
                $item->dispatch_volume,         // 'dispatch_volume'
                $item->rejected_quantity,       // 'rejected_quantity'
                $item->rejected_bulk,           // 'rejected_bulk'
                $item->rejected_volume,         // 'rejected_volume'
                $item->line                     // 'line'
                );

            fputcsv($output,  array_merge( $doc_arr, $item_arr) );

        }

        // Procede a copiar las respectivas fotos al directorio de salida
        $document_atta_obj = DocumentAttachment::where('id_document',
                            $doc_obj->id )->first();

        if ( empty ($document_atta_obj) )
        {

            Log::info("No se encontro attachments asociados.");
        }else{

            foreach (  array ('path', 'path1', 'path2', 'path3', 'path4',
            'path5') as $i )
            {
                #Log::info("Path $i [".$document_atta_obj->$i."]");
                if ( !empty( $document_atta_obj->$i ) )
                {
                    copy( $document_atta_obj->$i,
                    $path_to_save.
                    basename( $document_atta_obj->$i ));
                };
            };
        }

        fclose($output);

        //Solo para prilogic
        //Queque para poner archivo en carpeta compartida 
        // y envio de comando por ssh
        if($factory_obj[0]->code <= 999 && env('NEW_FLUJO_PRILOGIC') == true){

            SendFilesToPrilogic::dispatch([
                    'csvName'   => $csvFileName, 
                    'path'      => $path_to_save,
                    'date'      => date('Ymd'),
                    'docNumber' => $docNumber
                ])->onQueue('files_prilogic');

            Log::info('PRILOGIC - Enviado a la cola filesPrilogic');
        }

        return;

    }

}