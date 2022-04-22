<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
//use Log;
use Monolog\Logger;
use Monolog\Logger as Monolog;
use Monolog\Handler\StreamHandler;
use App\Models\Document;
use App\Models\Office;
use App\Models\DocumentAttachment;

class ReprocessReturnFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reporcess:return_files {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load ftp files to sistem';

    private $monolog;
    private $date;
    public function __construct()
    {
        parent::__construct();
        $date = date('Y-m-d');
        $monolog = new Logger(env('APP_ENV'));
       // $this->monolog = Log::getMonolog();
        $monolog->pushHandler(new StreamHandler(storage_path("logs/reprocess/reprocess-{$date}.log"), Monolog::INFO));
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
        $office = Office::findByCodeOrCreate('4000');
        $disk_return = Storage::disk('return_file');
        $date = $this->option('date');
        $this->monolog->info("REPROCESANDO ".$date);

        $company = strtolower($office->label);
        try {
            
            $files = $disk_return->Files("{$company}/{$date}");
            $isError = false;
        } catch (\Exception $e) {
            $this->monolog->info("Host FTP no responde");
            $isError = true;
        }

        if($isError){
            return;
        }
        $this->monolog->info("Archivos a reporcesar",$files);
        foreach ($files as $file) {
            $file = explode('/', $file);
            $file = $file[count($file)-1];

            $this->monolog->info("REPROCESANDO ->".$file);
            //separo carpeta de nombre Ej. qaportal/prisa/20180831083310.csv
            $fileName = explode('_', $file);
            $document_code = $fileName[1];

            $document = Document::join('routes', 'routes.id', '=','documents.route_id')
                ->where([
                ['documents.code', '=', $document_code],
                ['routes.office_id', '=', $office->id],

            ])
            ->select(['documents.id'])
            ->first();

            if(is_null($document)){
                $this->monolog->info("documento no encontrado ->".$file);
                continue;
            }

            $this->monolog->info("ATTACH DOCUMENT->".$document->id);
            $pathToPut = "{$company}/{$date}/{$document_code}";
            $attachments = DocumentAttachment::where('document_id', '=',$document->id)->get();
            $this->monolog->info("BUSCANDO attachments->",[$attachments]);
            if(is_null($attachments)){
                $this->monolog->info("attachments no encontrado ->".$document->id);
                continue;
            }

            $file_local = $disk_return->get("{$company}/{$date}/{$file}");
            $ftpConnet  = Storage::disk( env('DISK_BRIDGE') );
            $file_ftp = $ftpConnet->put("{$pathToPut}/{$file}", $file_local);
            foreach ($attachments as $key => $value) {
                $disk_local = Storage::disk('public');
                $pathToLocal = str_replace("storage/", "", $value->path);
                $file_local = $disk_local->get($pathToLocal);
                $fileFtpName = explode('/', $value->path);
                $fileFtpName = $fileFtpName[count($fileFtpName)-1];
                $this->monolog->info("PIC ->".$fileFtpName);
                $file_ftp = $ftpConnet->put("{$pathToPut}/{$fileFtpName}", $file_local);
            }
            $this->monolog->info("FIN REPROCESO ->".$file);
        }
    }
   
}