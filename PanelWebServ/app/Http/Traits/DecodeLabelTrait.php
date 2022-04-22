<?php
namespace App\Http\Traits;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Log;
use App\Models\DocumentarEnvio as DocumentarEnvio;

trait DecodeLabelTrait {

    public function decodeLabel($etiqueta,$numero_envio){

       $decode = base64_decode($etiqueta,true);
       
        $ot = strval($numero_envio);

        if (strpos($decode, '%PDF') !== 0) {
            throw new Exception('Missing the PDF file signature');
          }
          
          # Write the PDF contents to a local file
          $label =  file_put_contents('C:\xampp\htdocs\PanelWebServ\public\Ots\ '.$ot.'.pdf', $decode,);
          //fopen('C:\xampp\htdocs\PanelWebServ\public\Ots\ 910004938.pdf', 'r') or die ("Error");
           $locationLabel = 'C:\xampp\htdocs\PanelWebServ\public\Ots\ '.$ot.'.pdf';

          // Conseguimos el objeto
            $dirLabel = DocumentarEnvio::where('NUMERO_ENVIO', '=', $numero_envio)->first();
            
            // Si existe
            if(!empty($dirLabel)){
            // Seteamos la ubicacion de etiqueta
            $dirLabel->UBICACION_ETIQUETA = $locationLabel;
            
            // Guardamos en base de datos
            $dirLabel->save();
            }

    
        }
    

} 