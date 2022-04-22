<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use App\Http\Traits\DecodeManifestTrait;
use App\Models\DocumentarEnvio as DocumentarEnvio;

use DataTables;

class EntregaRecogedor extends Controller
{

    use DecodeManifestTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        Log::info("Tabla entrega");

        if($request->ajax()){ 
            
            $data = DocumentarEnvio::query()
            ->where('PROCESAR_ETIQUETADO', 1)
            ->select([
            'id AS id',
            'NUMERO_ENVIO AS NUMERO_ENVIO',
            'REFERENCIA AS REFERENCIA',
            'created_at AS created_at',
            'REFERENCIA AS REFERENCIA'
    
            ]);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                   
                    $check = '<label class="customcheck"><input type="checkbox" name="order_check[]" id="' . $data->id . '" value="' . $data->NUMERO_ENVIO . '" class=""><span class="checkmark"></span></label>';
                   
                    return $check; 
                })
                ->rawColumns(['action'])
                ->make(true);
    
        }

    }

    public function deliveryManagement(Request $request){

        $array_check = $request->input('order_check');    //Array de checkbox de Ordenes de Traslado
        Log::info($array_check);

        foreach ($array_check as $key => $value) {
            # code...
           // Log::info($value);
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://gtstntpre.alertran.net/gts/seam/resource/restv1/auth/entregaRecogedorService/entregaRecogida",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"\r\n{\r\n\"ENTREGAS\" : {\r\n\t\t\"ENTREGA\" : [\r\n\t\t{\"CLIENTE\": \"900072034\",\r\n\t\t  \"CENTRO\": \"01\",\r\n\t\t  \"EXPEDICION\": \"".$value."\", \r\n\t\t  \"ENVIO_CON_RECOGIDA\": \"S\",\r\n\t\t  \"MANIFIESTO\": \"S\"\r\n\t\t  }\r\n\t\t]\r\n}\r\n}\r\n\r\n",
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic U1BFUkVaOkZlZGV4LjQzMjI=',
                'Content-Type: application/json',
                'Cookie: JSESSIONID=7imGB1JlwwxDhfn85BELt92XYqmbphtWGU9yCmLu.master:backend-tnt_gts'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            echo $response;
            Log::info($response);

            $reponseFedex =  (array)json_decode($response, true);
  

            foreach ($reponseFedex as $key => $value) {
                   # code...
               $resultado =         $value['respuestaEntregaRecogida']['resultado'];
               $numero_envio =      $value['respuestaEntregaRecogida']['expedicion'];
               $numero_recogida =   $value['respuestaEntregaRecogida']['recogida'];
               $mensaje =           $value['respuestaEntregaRecogida']['mensaje'];
               $manifiesto =        $value['respuestaEntregaRecogida']['manifiesto'];
              // $procesarEtiqueta = 0;
               }

               $Request = new EntregaRecogedor();
               $Request->decodeManiest($manifiesto,$numero_recogida);



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
