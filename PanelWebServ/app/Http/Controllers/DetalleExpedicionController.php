<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentarEnvio;
use DataTables;

use Illuminate\Support\Facades\Log;

class DetalleExpedicionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
   
        if($request->ajax()){ 
    
            //$data = DocumentarEnvio::latest()->get();
    
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
                    $button = '<button type="button" name = "Imprimir" class="btn btn-success btn-sm mostrarRuta" data-id ="'.$data->id.'"   data-toggle="modal" data-target="#staticBackdrop" ><i class="now-ui-icons location_map-big"></i><a> Ver</a></button>';
                    return $button; 
                })
                ->rawColumns(['action'])
                ->make(true);
    
    
                
    
        }
    }

    public function showRoute(Request $request){

        $expedicionId =  $request->input('Item_id');

        Log::info($expedicionId);
        Log::info("Detalle expedicion");

        $data = DocumentarEnvio:: where('id', '=', $expedicionId)->first();

        //Obtengo la OT
        $ot = $data->NUMERO_ENVIO;


        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://gtstntpre.alertran.net/gts/seam/resource/restv1/auth/detalleExpedicioneService/detalles",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>"{\n    \"DETALLES_EXPEDICION\": {\n        \"DETALLE_EXPEDICION\": [\n            {\n                \"CLIENTE\": \"900007424\",\n                \"CENTRO\": \"01\",\n                \"EXPE_NUMERO\": \"".$ot."\"\n            }\n        ]\n    }\n}",
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic U1BFUkVaOkZlZGV4LjQzMjI=',
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
                        



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
