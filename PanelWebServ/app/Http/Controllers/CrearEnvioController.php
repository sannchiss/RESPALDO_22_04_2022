<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\DocumentarEnvio;
use App\Http\Requests;
use App\Http\Traits\DocumentarEnvioTrait;
use App\Http\Traits\DecodeLabelTrait;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use DataTables;

class CrearEnvioController extends Controller
{
    use DocumentarEnvioTrait;
    use DecodeLabelTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Log::info("Crear envio");
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://gtstntpre.alertran.net/gts/seam/resource/restv1/auth/documentarEnvio/json",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true, 
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>"{\n    \"DOCUMENTAR_ENVIOS\": {\n        \"DOCUMENTAR_ENVIO\": [\n            
            {\n
                \"REFERENCIA\": \"".$request->input('REFERENCIA')."\",
                \n\"NUMERO_ENVIO\": \"".$request->input('NUMERO_ENVIO')."\",\n\"CODIGO_ADMISION\":\"".$request->input('CODIGO_ADMISION')."\",\n\"NUMERO_BULTOS\": \"".$request->input('NUMERO_BULTOS')."\",\n\"CLIENTE_REMITENTE\": \"".$request->input('CLIENTE_REMITENTE')."\",\n\"CENTRO_REMITENTE\":\"".$request->input('CENTRO_REMITENTE')."\",\n\"NIF_REMITENTE\": \"".$request->input('NIF_REMITENTE')."\",\n                \"NOMBRE_REMITENTE\": \"".$request->input('NOMBRE_REMITENTE')."\",\n\"DIRECCION_REMITENTE\": \"".$request->input('DIRECCION_REMITENTE')."\",\n\"PAIS_REMITENTE\": \"CL\",
                \n\"CODIGO_POSTAL_REMITENTE\": \"".$request->input('CODIGO_POSTAL_REMITENTE')."\",
                \n\"POBLACION_REMITENTE\": \"".$request->input('POBLACION_REMITENTE')."\",\n\"PERSONA_CONTACTO_REMITENTE\": \"$request->input('PERSONA_CONTACTO_REMITENTE')\",\n                \"TELEFONO_CONTACTO_REMITENTE\": \"997416303\",\n                \"EMAIL_REMITENTE\": \"sannchiss@gmail.COM\",\n                \"NIF_DESTINATARIO\": \"1-9\",\n\"NOMBRE_DESTINATARIO\": \"TEST DESTINATARIO\",\n\"DIRECCION_DESTINATARIO\": \"SANTA CATALINA DE CHENA, 1001\",\n                \"PAIS_DESTINATARIO\": \"CL\",\n\"CODIGO_POSTAL_DESTINATARIO\": \"8320000\",\n\"POBLACION_DESTINATARIO\": \"SANTIAGO\",\n                \"PERSONA_CONTACTO_DESTINATARIO\": \"TEST CONTACTO\",\n                \"TELEFONO_CONTACTO_DESTINATARIO\": \"999999999\",\n                \"EMAIL_DESTINATARIO\": \"none@none.com\",\n                \"CODIGO_PRODUCTO_SERVICIO\": \"01\",\n\"KILOS\": \"4\",\n\"VOLUMEN\": \"1\",\n\"CLIENTE_REFERENCIA\": \"".$request->input('CLIENTE_REFERENCIA')."\",\n\"IMPORTE_REEMBOLSO\": 0,\n\"IMPORTE_VALOR_DECLARADO\": \"33500\",\n\"TIPO_PORTES\": \"P\",\n\"OBSERVACIONES1\": \"PRUEBA GRABACION\",\n\"OBSERVACIONES2\": \"OBS2\",\n\"TIPO_MERCANCIA\": \"P\",\n\"VALOR_MERCANCIA\": \"200000\",\n                \"MERCANCIA_ESPECIAL\": \"N\",\n\"GRANDES_SUPERFICIES\": \"N\",\n                \"PLAZO_GARANTIZADO\": \"N\",\n\"LOCALIZADOR\": \"\",\n                \"NUM_PALETS\": 0,\n                \"FECHA_ENTREGA_APLAZADA\": \"\",\n                \"ENTREGA_APLAZADA\": \"N\",
                \n                
                \"TIPOS_DOCUMENTO\": [\n                    {\n                        \"TIPO\": \"FACT\",\n                        \"REFERENCIA\": \"FACT-123\"\n                    },\n                    {\n                        \"TIPO\": \"GD\",\n                        \"REFERENCIA\": \"456\"\n                    }\n                ],\n                 
                \"GESTION_DEVOLUCION_CONFORME\": \"N\",\n                \"ENVIO_CON_RECOGIDA\": \"N\",\n                \"IMPRIMIR_ETIQUETA\": \"S\",\n                \"ENVIO_DEFINITIVO\": \"N\",\n                \"TIPO_FORMATO\": \"PDF2\"\n            }\n        ]\n    }\n}",
                CURLOPT_HTTPHEADER => array(
                "Authorization: Basic U1BFUkVaOkZlZGV4LjQzMjI=",
                "Content-Type: application/json",
                "Cookie: JSESSIONID=WyrxkQxRMYmJ3nLb30ThxXJM-Qk9MQskN1BupCW4.master:backend-tnt_gts"
                  ),
        ));

        $response = curl_exec($curl);
        Log::info($response);          
        $reponseFedex =  (array)json_decode($response, true);
  

     foreach ($reponseFedex as $key => $value) {
            # code...
        $resultado =        $value['respuestaDocuemtarEnvio']['resultado'];
        $codigo_admision =  $value['respuestaDocuemtarEnvio']['codigo_admision'];
        $numero_envio =     $value['respuestaDocuemtarEnvio']['numero_envio'];
        $mensaje =          $value['respuestaDocuemtarEnvio']['mensaje'];
        $etiqueta =         $value['respuestaDocuemtarEnvio']['etiqueta'];
        $procesarEtiqueta = 0;
        }


        if($etiqueta.empty(false)){
            Log::info("Etiqueta Llena");
        }

        $Request = new CrearEnvioController();
        //Log::info($etiqueta);
        $Request->insertRequest($request,$numero_envio,$etiqueta,$procesarEtiqueta);
        $Request->decodeLabel($etiqueta, $numero_envio);

        return $response;

    }


public function query(Request $request){

    if($request->ajax()){ 
    
        //$data = DocumentarEnvio::latest()->get();
        Log::info("Envios....");
        $data = DocumentarEnvio::query()
        //->where('PROCESAR_ETIQUETADO', 0)
        ->select([
        'id AS id',
        'NUMERO_ENVIO AS NUMERO_ENVIO',
        'REFERENCIA AS REFERENCIA',
        'created_at AS created_at',
        'REFERENCIA AS REFERENCIA',
        'PROCESAR_ETIQUETADO AS PROCESAR_ETIQUETADO'
        ]);

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($data){
                Log::info($data->PROCESAR_ETIQUETADO);
                if($data->PROCESAR_ETIQUETADO == '0'){
                $button = '<button type="button" name = "Imprimir" class="btn btn-success btn-sm imprimirPDF" data-id ="'.$data->id.'"><i class="now-ui-icons files_paper"></i><a>Imprimir</a></button>';
                }else
                {
                    $button = '<button type="button" name = "Imprimir" class="btn btn-success btn-sm imprimirPDF" data-id ="'.$data->id.'"><i class="now-ui-icons files_paper"></i><a>Re-Imprimir</a></button>';
                }
                $button .= ' <button type="button" name = "eliminar" class="btn btn-danger btn-sm anularEnvio" data-id ="'.$data->id.'"><i class="now-ui-icons ui-1_simple-remove"></i>Eliminar</button>';
                return $button; 
            })
            ->rawColumns(['action'])
            ->make(true);


            

    }

}

public function etiquetaPDF(Request $request){

    $Item_id = $request->input('Item_id');
    Log::info($Item_id);
     // Conseguimos el objeto
     $printLabel = DocumentarEnvio::where('id', '=', $Item_id)->first();
            
     // Si existe se  retorna direccion del PDF
     if(!empty($printLabel)){ 
         
        // Obtenemos la OT de nuestra BD
        $ot = $printLabel->NUMERO_ENVIO;
        // Obtenemos la Etiqueta en base 64 de la BD
        $label64 = $printLabel->ETIQUETA64;
        // Asignamos la carpeta donde esta ubicado el digital .PDF
        $filename = 'Ots\\ '.$ot.'.pdf';
        // Decimos que nuestra es la carpeta Public
        $path = public_path($filename);

        $contentType = mime_content_type($path);
        //Log::info($label64);
        $info = Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
            ]);
     
            /*--------------------------------------------------*/

            // Seteamos PROCESAR_ETIQUETADO, esto con el fin de sacar del listado todas las OTs que se hayan impreso.
                $printLabel->PROCESAR_ETIQUETADO = 1;
                            
            // Guardamos en base de datos
                $printLabel->save();

            /*--------------------------------------------------*/
                return $label64;


     }
 
    




}







    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
