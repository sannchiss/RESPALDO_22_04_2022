<?php
namespace App\Http\Traits;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Log;
use App\Models\DocumentarEnvio as DocumentarEnvio;


trait DecodeManifestTrait {

public function decodeManiest($manifiesto,$numero_recogida){

    $decode = base64_decode($manifiesto,true);

    $recogida = strval($numero_recogida);

    if (strpos($decode, '%PDF') !== 0) {
        throw new Exception('Missing the PDF file signature');
      }

        $file = 'C:\xampp\htdocs\PanelWebServ\public\Manifiestos\ '.$recogida.'.pdf';
          if( is_file($file) ){

         /*    if (unlink($file)) {
              // file was successfully deleted
              Log:info("Manifiesto $recogida Eliminado");

            } else {
              // there was a problem deleting the file
              //Log:info("Problema para eliminar el manifiesto: $recogida ");

            }
 */



            Log:info("Existe");
          }else{
            Log::info("No existe");
          }

      # Write the PDF contents to a local file
      $manifest =  file_put_contents('C:\xampp\htdocs\PanelWebServ\public\Manifiestos\ '.$recogida.'.pdf', $decode);
      //fopen('C:\xampp\htdocs\PanelWebServ\public\Ots\ 910004938.pdf', 'r') or die ("Error");
      $locationLabel = 'C:\xampp\htdocs\PanelWebServ\public\Manifiestos\ '.$recogida.'.pdf';
      

}



}