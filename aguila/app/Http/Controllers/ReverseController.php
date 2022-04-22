<?php

namespace App\Http\Controllers;
use App\Models\Vehicle;
use App\Models\Route;
use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use DataTables;
use Illuminate\Http\Request;

class ReverseController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
       // Extraigo la lista de patentes que poseen rutas en estatus: Reparto
       $code = Vehicle::query()
       ->join('routes','routes.vehicle_id','=','vehicles.id')
       ->join('documents','documents.route_id','=','routes.id')
       ->where('documents.status_id','!=',5)
       ->select([
           'vehicles.code',
           'vehicles.id',
       ])
       ->groupBy('vehicles.code','vehicles.id')
       ->orderByRaw('vehicles.code::INTEGER ASC')->get();
       return view('reverse.index',['code' => $code]);
    }

    public function document(Request $request){
        
       // Extraigo la lista de los documentos que estan en estatus: Reparto
        $code_id = $request->codigo_id;
       
            $data = Route::query()
            ->join('documents','routes.id', '=', 'documents.route_id')
            ->join('vehicles','routes.vehicle_id','=','vehicles.id')
            ->join('statuses','documents.status_id','=','statuses.id')
            ->where('routes.vehicle_id', '=',$code_id)
            ->where('documents.status_id','!=',5)
    		->select([
                'documents.id AS item',
                'documents.order_number AS order',
                'documents.code AS doc_code',
                'routes.code AS code',
                'vehicles.code AS vehicle',
                'statuses.label AS label',
                'statuses.color AS color'
            ])
            ->groupBy('documents.id','documents.order_number', 'routes.code','vehicles.code','statuses.label','statuses.color')
            ->orderByRaw('routes.code ASC');  

            if(request()->ajax()){      
            return DataTables::of($data)
                    ->addColumn('action', function($data){
                  
                        $check = '<label class="customcheck"><input type="checkbox" name="doc_check[]" id="'.$data->item.'" value="'.$data->order.'" class=""><span class="checkmark"></span></label>';

                        return $check;
                    })
                    ->editColumn('label', function ($data) {
                        return "<h5><span class='badge badge-{$data->color}'>
                                    {$data->label}
                                </span></h5>";
                    })
                    ->rawColumns(['action','label'])
                    ->make(true);
        
            }
                    return view('reverse.index', ['datos' => $data->get()]);
    }

    public function route(Request $request){

        $route = $request->input('code');

        $data = Route::query()
        ->join('documents','routes.id', '=', 'documents.route_id')     
        ->where('routes.vehicle_id', '=', $route)
        ->where('documents.status_id','!=',5)
        ->select([
            'routes.code AS code',
            'routes.id AS id'
        ])

        ->groupBy('routes.code','routes.id')
        ->orderByRaw('routes.code ASC')->get();
        return $data;

    }

    public function reload(Request $request){

        $patente = $request->input('codigo_id');
        $ruta = $request->input('code_route');

        if($ruta==0){
           $data = Route::query()
           ->join('documents','routes.id', '=', 'documents.route_id')
           ->join('vehicles','routes.vehicle_id','=','vehicles.id')
           ->join('statuses','documents.status_id','=','statuses.id')
           ->where('routes.vehicle_id', '=',$patente)
           ->where('documents.status_id','!=',5)
           ->select([
               'documents.id AS item',
               'documents.order_number AS order',
               'documents.code AS doc_code',
               'routes.code AS code',
               'vehicles.code AS vehicle',
               'statuses.label AS label',
               'statuses.color AS color'
          ])
          ->groupBy('documents.id','documents.order_number', 'routes.code','vehicles.code','statuses.label','statuses.color')
          ->orderByRaw('documents.order_number::integer ASC');  
   
           if(request()->ajax()){      
           return DataTables::of($data)
                   ->addColumn('action', function($data){
                      
                    $check = '<label class="customcheck"><input type="checkbox" name="doc_check[]" id="'.$data->item.'" value="'.$data->order.'" class=""><span class="checkmark"></span></label>';
   
                       return $check;
                   })
                   ->editColumn('label', function ($data) {
                    return "<h5><span class='badge badge-{$data->color}'>
                                {$data->label}
                            </span></h5>";
                })

                    ->rawColumns(['action','label'])
                   ->make(true);
           }
                   return view('reverse.index', ['datos' => $data->get()]);
                   Log::info($data);

        }
        else{
            $data = Route::query()
            ->join('documents','routes.id', '=', 'documents.route_id')
            ->join('vehicles','routes.vehicle_id','=','vehicles.id')
            ->join('statuses','documents.status_id','=','statuses.id')
            ->where('routes.vehicle_id', '=',$patente)
            ->where('documents.status_id','!=',5)
            ->where('routes.id', '=', $ruta)
            ->select([
                'documents.id AS item',
                'documents.order_number AS order',
                'documents.code AS doc_code',
                'routes.code AS code',
                'vehicles.code AS vehicle',
                'statuses.label AS label',
                'statuses.color AS color'
            ])
            ->groupBy('documents.id','documents.order_number', 'routes.code','vehicles.code','statuses.label','statuses.color')
            ->orderByRaw('documents.order_number::integer ASC');  

            if(request()->ajax()){      
                    return DataTables::of($data)
                    ->addColumn('action', function($data){
                    
                        $check = '<label class="customcheck"><input type="checkbox" name="doc_check[]" id="'.$data->item.'" value="'.$data->order.'" class=""><span class="checkmark"></span></label>';

                        return $check;
                    })
                    ->editColumn('label', function ($data) {
                        return "<h5><span class='badge badge-{$data->color}'>
                                    {$data->label}
                                </span></h5>";
                    })
                    ->rawColumns(['action','label'])
                    ->make(true);
            }
                    return view('reverse.index', ['datos' => $data->get()]);
            }
    }

    public function checkdocument(Request $request){
        $array_check = $request->input('doc_check');          //Id documents (Array de checkbox)
        $status =      $request->status_button;               //Estatus boton selección
        $patente_id =  $request->patente_id;                  //Id Patente
        $route_id =    $request->route;                       //Id Route
        $selectCheck = $request->selectCheck;                 //CheckBox Selección

        Log::info(json_encode($array_check));
        Log::info($patente_id);
        
        if(empty($selectCheck)){

            foreach ($array_check as $lista_check) {
                        
                    Document::where('order_number',$lista_check)
                    ->update([
                        'status_id' => $status
                    ]);

                    $flat_route = DB::selectOne("
                    WITH data AS (
                        SELECT
                            routes.id
                        FROM routes
                            JOIN documents ON documents.route_id = routes.id
                        WHERE documents.order_number = '{$lista_check}' AND routes.vehicle_id = '{$patente_id}'
                    )
                    SELECT 
                        documents.order_number,
                        documents.status_id,
                        routes.id
                    FROM documents
                    JOIN routes ON routes.id = documents.route_id
                    JOIN data ON data.id = routes.id
                    GROUP BY documents.order_number,documents.status_id,routes.id
                    ORDER BY documents.status_id DESC
                ");
                
                    if ($flat_route->status_id == 5){
                           // Log::info("Cambio status de la ruta");
                            Route::query()
                            ->where('id',$flat_route->id)
                            ->update([
                                'status_id' => 2,
                                'updated_at'=> DB::raw('now()')
                            ]);
                        }
                    }
                    }else{
                            Document::where('route_id',$route_id)
                            ->update([
                                'status_id' => $status
                            ]);
                            Route:: where('id',$route_id)
                            ->update([
                                'status_id' => 2
                            ]);
                        
                    }

                }
}
