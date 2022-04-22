<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use App\Models\DocumentAttachment;
use DB;

class ReturnFilesToCompany implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $document;
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $document = $this->document;
        $csvHead = [
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
            'other',
            'product',
            'code',
            'p_status',
            'p_status_des',
            'p_dispatch_quantity',
            'p_dispatch_bulk',
            'p_dispatch_volume',
            'p_rejected_quantity',
            'p_rejected_bulk',
            'p_rejected_volume',
            'line'
        ];

        $csvData = DB::select("
            SELECT 
                CASE WHEN offices.code = '4000' THEN customers.code ELSE offices.code END AS empresa,
                lower(offices.label) AS office,
                documents.created_at AS uploaded_date,
                documents.processed_date AS delivery_date,
                documents.code AS document,
                docstatuses.label AS status,
                docstatusreasons.label AS status_des,
                vehicles.code AS plate_number,
                driver.code AS employee,
                auxiliary.code AS employee2,
                documents.units AS dispatch_quantity,
                documents.packages AS dispatch_bulk,
                documents.products AS dispatch_volume,
                documents.rejected_units AS rejected_quantity,
                documents.rejected_packages AS rejected_bulk,
                documents.rejected_products AS rejected_volume,
                customer_branches.label AS customer,
                customer_branches.rut AS customer_code,
                customer_branches.code AS customer_subcode,
                customer_branches.lat AS latitude,
                customer_branches.lon AS longitude,
                routes.code AS route_sheet,
                documents.order_number,
                documents.updated_at AS modified,
                documents.received_by AS recibe,
                documents.observation AS other,
                products.label AS product,
                products.code AS code,
                detailstatuses.label AS p_status,
                detailstatusreasons.label AS p_status_des,
                document_details.quantity AS p_dispatch_quantity,
                '0' AS p_dispatch_bulk,
                '0' AS p_dispatch_volume,
                document_details.quantity_rejected AS p_rejected_quantity,
                '0' AS p_rejected_bulk, 
                '0' AS p_rejected_volume,
                document_details.row_order AS line
            FROM documents 
                JOIN statuses AS docstatuses ON docstatuses.id = documents.status_id
                LEFT JOIN status_reasons AS docstatusreasons ON docstatusreasons.id = documents.status_reason_id
                JOIN routes ON routes.id = documents.route_id
                JOIN offices ON offices.id = routes.office_id
                JOIN vehicles ON vehicles.id = routes.vehicle_id
                JOIN employees AS driver ON driver.id = routes.driver_id 
                LEFT JOIN employees AS auxiliary ON auxiliary.id = routes.auxiliary_id 
                JOIN customer_branches ON customer_branches.id = documents.customer_branch_id
                JOIN customers ON customers.id = customer_branches.customer_id
                JOIN document_details ON document_details.document_id = documents.id
                JOIN products ON products.id = document_details.product_id
                JOIN statuses AS detailstatuses ON detailstatuses.id = document_details.status_id
                LEFT JOIN status_reasons AS detailstatusreasons ON detailstatusreasons.id = document_details.status_reason_id
            WHERE documents.id = {$document->id}
        ");

        $date = date('Ymd');
        $time = date('His');
        $fileName = "{$date}{$time}_{$csvData[0]->document}_{$csvData[0]->empresa}.csv";
        $basePath = "return_file/{$csvData[0]->office}/{$date}";
        $path = storage_path("app/{$basePath}");

        if(!file_exists($path)){
            mkdir($path,0775,true);
        }
        $filePath = "{$path}/{$fileName}";
        $outputCsv = fopen($filePath, 'w');
        fputcsv($outputCsv,  $csvHead );

        foreach ($csvData as $value) {
            $row = [];
            $i = 0;
            foreach ($csvHead as $field) {
                $row[] = $value->{$field};
            }
            fputcsv($outputCsv,  $row);
        }
        fclose($outputCsv);

        try {
            
            //Se envia archivo al ftp 
            $disk_local = Storage::disk('local');
            $file_local = $disk_local->get("/{$basePath}/{$fileName}");
            $pathToPut = "{$csvData[0]->office}/{$date}/{$csvData[0]->document}";
            $ftpConnet  = Storage::disk( env('DISK_BRIDGE') );
            $file_ftp = $ftpConnet->put("{$pathToPut}/{$fileName}", $file_local);

            $attachments = DocumentAttachment::where('document_id', '=',$document->id)->get();

            foreach ($attachments as $key => $value) {

                $disk_local = Storage::disk('public');
                $pathToLocal = str_replace("storage/", "", $value->path);
                $file_local = $disk_local->get($pathToLocal);
                $fileFtpName = explode('/', $value->path);
                $fileFtpName = $fileFtpName[count($fileFtpName)-1];
                $file_ftp = $ftpConnet->put("{$pathToPut}/{$fileFtpName}", $file_local);
            }
        } catch (\Exception $e) {
            \Log::info('ERROR conectando al ftp -> '.$e->getMessage());
        }


    }


}
