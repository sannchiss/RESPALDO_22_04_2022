<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\DocumentarEnvio;


class AnularEnvioController extends Controller
{
    //
    public function index(Request $request){

        $Item_id = $request->input('Item_id');
        //Log::info($Item_id);
        $borrarExpedicion = DocumentarEnvio::find($Item_id);

        $numExpedicion = $borrarExpedicion->NUMERO_ENVIO;

        Log::info($numExpedicion);

        if(!empty($numExpedicion)){
            

            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://gtstntpre.alertran.net/gts/seam/resource/restv1/auth/anularWebExpediciones/anular",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS =>"\r\n    {\r\n    \"webExpediciones\": {\r\n    \"numeroWebExpedicion\": \"".$numExpedicion."\",\r\n    \"clienteOrigen\":\"900072034\"\r\n                    }\r\n    }\r\n\r\n",
              CURLOPT_HTTPHEADER => array(
                "Authorization: Basic U1BFUkVaOkZlZGV4LjQzMjI=",
                "Content-Type: application/json"
              ),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);

            $borrarExpedicion->delete();

            return $response;



        }





    }




}
