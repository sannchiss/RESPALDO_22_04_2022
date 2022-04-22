<?php
namespace App\Http\Controllers\Api\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use App\Models\Cellphone;
use App\Models\EmployeeType;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Models\Document;
use App\Models\DocumentDetail;
use App\Models\DocumentAttachment;
use App\Models\CurrentCellphoneStatus;
use App\Models\Route;
use App\Models\App;
use App\Models\Status;
use App\Libs\SqliteGenerator;
use App\Jobs\RouteInfoProcess;
use App\Jobs\TripInfoProcess;
use Cache;
use App\Jobs\ReturnFilesToCompany;



class MainAppController extends Controller {

    public function test($id){
        $document = Document::find($id);
        ReturnFilesToCompany::dispatch($document)->onQueue('files_return');
    }

    public function check(Request $request){
        \Validator::make($request->all(), [
            'imei' => 'required'
        ])->validate();

        $imei = $request->input('imei');

        $exists = Cellphone::findByImei($imei);

        return response()->json([
            'exists'    => is_null($exists) ? false : true,
            'appInfo'   => App::appInfo(),
        ],200);
    }

	public function checkIn(Request $request){
		\Validator::make($request->all(), [
            'imei' => 'required'
        ])->validate();

        $imei = $request->input('imei');
        $imsi = $request->input('imsi');
        $phoneNumber = $request->input('phoneNumber');
        $operatorName = $request->input('operatorName');
        $phoneModel = $request->input('phoneModel');
        $phoneBrand = $request->input('phoneBrand');
        $phoneOs = $request->input('phoneOs');
        $osVersion = $request->input('osVersion');
        $officeCode   = $request->input('companyCode');
        $officeLabel   = strtoupper($request->input('companyLabel'));

        if(is_null(Cellphone::findByImei($imei))){
            $device = Cellphone::findByCodeOrCreate($imei,$imsi,$phoneNumber,$operatorName,$phoneModel,$phoneBrand,$phoneOs,$osVersion, $officeCode, $officeLabel);
        }

        $EmployeeTypes = EmployeeType::get();

        $Employee = DB::select("
            SELECT
            emp.*
            FROM
            employees AS emp
            JOIN cellphones as cell
            ON emp.id = cell.employee_id
            WHERE cell.imei = '{$imei}'
        ");

        $Vehicles = Vehicle::get();

        $Vehicle = DB::select("
            SELECT
            veh.*
            FROM
            vehicles AS veh
            JOIN cellphones as cell
            ON veh.employee_id = cell.employee_id
            WHERE cell.imei = '{$imei}'
        ");

        //Log::info($Vehicles);

        return response()->json([
            'employee_types'    => $EmployeeTypes,
            //'employees'         => '',//$Employees,
            'employee_actual'   => $Employee,
            'vehicles'          => $Vehicles,
            'vehicle_actual'    => $Vehicle
        ],200);

	}

    public function updateDriver(Request $request){


        \Validator::make($request->all(), [
            'imei' => 'required',
            'driver_code' => 'required'
        ])->validate();

        $imei = $request->input('imei');
        $codeDriver = $request->input('driver_code');
        $codeAux    = $request->input('aux_code');

        $cellphone = Cellphone::findByImei($imei);

        $driver = Employee::findByOfficeTypeCode($cellphone->office_id, 'DRIVER', $codeDriver);

        if(is_null($driver)){
            return response()->json([
                'error'    => 'Conductor no registrado',
            ],200);
        }

        $aux    = Employee::findByOfficeTypeCode($cellphone->office_id, 'AUXILIARY', $codeAux);
        $cellphone->employee_id  = $driver->id ?? null;
        $cellphone->auxiliary_id = $aux->id ?? null;

        $cellphone->save();

        Cache::forget("cellphone:{$imei}");

        return response()->json([
            'code_driver'    => $codeDriver,
        ],200);

    }

    public function updateVehicle(Request $request){
        \Validator::make($request->all(), [
            'imei' => 'required',
            'patente' => 'required'
        ])->validate();

        $plateNumber = $request->input('patente');
        $imei = $request->input('imei');
        $cellphone = Cellphone::findByImei($imei);
        
        $vehicle = Vehicle::findByCodeAndUpdateOffice($plateNumber, $cellphone->office_id);
        $vehicle->employee_id = $cellphone->employee_id;    
        $vehicle->save();

        $checkRouteList = Route::join('documents','documents.route_id', '=', 'routes.id' )
                        ->join('vehicles',function($join) use($plateNumber){
                            $join->on('vehicles.id', '=','routes.vehicle_id')
                                ->on('vehicles.code','=', DB::raw("'{$plateNumber}'"));
                        })
                        ->join('offices', 'offices.id', '=', 'routes.office_id')
                        ->join('statuses',function($join){
                            $join->on('statuses.id', '=','routes.status_id')
                                ->on('statuses.code','=', DB::raw("'PENDING_DEPARTURE'"));
                        })
                        ->groupBY(['documents.route_id', 'routes.code', 'routes.office_id', 'offices.label'])
                        ->select(['documents.route_id as id', DB::raw('routes.code::varchar as code'), 'routes.office_id', 'offices.label'])
                        ->get();
        
        $routeList = null;
        $message = '';
        if(count($checkRouteList) > 0 ){
            $routeList = $checkRouteList->where('office_id', '=', $cellphone->office_id);
            if(count($routeList) < 1){
                $message ="Vehiculo {$plateNumber} pertenece a la oficina {$checkRouteList[0]->label}";
            }
        }else{
            $message = 'Vehiculo sin rutas asignadas';
        }


        //se actualiza datos del documento 
        if(!is_null($routeList)){
            Route::whereIn('id', $routeList->pluck('id'))->update([
                'driver_id' => $cellphone->employee_id,
                'auxiliary_id' => $cellphone->auxiliary_id ?? null,
            ]);
        }

        return response()->json([
            'patente'       => $plateNumber,
            'route_list'    => is_null($routeList) ? [] : $routeList->pluck('code'),
            'message'       => $message,
        ],200);
    }

    public function getDB(Request $request){
        \Validator::make($request->all(), [
            'patente' => 'required',
            'routes'  => 'required'
        ])->validate();

        $vehicle_code = $request->input('patente');
        $routes = $request->input('routes');


        $sqlite = new SqliteGenerator($vehicle_code,$routes);
        $path = $sqlite->getDownloadUrl();

        Log::info($path);

        return response()->json([
            'patente'    => $vehicle_code,
            'path'       => $path,
        ],200);

    }

    public function getOperation(Request $request){
        //pic5 es firma
        \Validator::make($request->all(), [
            'request' => 'required'
        ])->validate();

        $data_request    = (object) json_decode($request->input('request'),true);
        $params          = (object) $data_request->params;
        $document_code   = $params->document_code;
        $processed_date  = $params->process ??  date('Y-m-d H:i:s');

        //validacion de imagen
        $pics      = [];
        $picError  = null;
        for ($i=1; $i < 6; $i++) { 
            $pic   = $request->file("pic{$i}");
            if (!empty($pic)) {
                if(!$pic->isValid()){
                    $picError = 'error al subir imagen, no valida';
                    break;
                }
                $pics[] = [
                    'pic'   => $pic,
                    'class' => $i == 5 ? 'DOCUMENT_SIGN' : 'DOCUMENT_PIC'
                ];
            }
        }

        if( count($pics) == 0 || !is_null($picError) ){
            \Log::info($picError ?? 'Se debe subir al menos una foto del documento');
            return response()->json([
                'error'    => $picError ?? 'Se debe subir al menos una foto del documento'
            ],200);
        }
        //fin validacion imagenes

        //Actualizacion de documentos
        $documentStatus = Status::findByCode('DOCUMENT',$params->status_code)->id;
        $document = Document::where('id', $params->document_id)->first();
        $document->status_id      = $documentStatus;
        $document->processed_date = $processed_date;
        $document->received_by    = $params->nombre_recibe;
        $document->observation    = $params->otro;
        $document->save();

        //Obtengo la ruta y dependiendo si todos los documentos cambian a condición diferente de Reparto, 
        //Cambio el estatus de la ruta
        $flat_route = DB::selectOne("
        WITH data AS (
           SELECT
               routes.id
           FROM routes
               JOIN documents ON documents.route_id = routes.id
           WHERE documents.id = '{$params->document_id}'
           )
           SELECT 
               documents.order_number,
               documents.status_id,
               routes.id AS id
           FROM documents
               JOIN routes ON routes.id = documents.route_id
               JOIN data ON data.id = routes.id
            GROUP BY documents.order_number,routes.id,documents.status_id
            ORDER BY documents.status_id ASC");
            Log::info(json_encode($flat_route->id));
                
           if ($flat_route->status_id > 5) {
               Log::info("Cambio de Estatus");
                Route::whereId($flat_route->id)
                ->update([
                    'status_id' => 26
                ]);

           }

        //actualizacion detalles del documento
        if($params->status_code != 'PARTIAL_REJECTION'){//Solo Entrega Total, Rechazo total y Redespacho
            switch ($params->status_code) {
                case 'ACCEPTED':
                    $quantity_accepted = DB::raw('quantity');
                    $quantity_rejected = 0;
                    break;
                case 'REJECTED':
                    $quantity_accepted = 0;
                    $quantity_rejected = DB::raw('quantity');
                    break;
                default:
                    $quantity_accepted = 0;
                    $quantity_rejected = 0;
                    break;
            }
            $status_reason_id = $params->reason_id ?? null;

            DocumentDetail::where('document_id', '=', $params->document_id)
                ->update([
                    'status_id'         => Status::findByCode('DOCUMENT_DETAIL',$params->status_code)->id,
                    'status_reason_id'  => $status_reason_id < 1 ? null : $status_reason_id,
                    'quantity_accepted' => $quantity_accepted,
                    'quantity_rejected' => $quantity_rejected,
                ]);
        } else {
            //TODO
            $detailsRejected = [];
            foreach ($params->products as $value) {
                $detailsRejected[] = $value['id'];
                DocumentDetail::where('id', '=', $value['id'])
                    ->update([
                        'status_id'         => $value['status_id'],
                        'status_reason_id'  => $value['reason_id'],
                        'quantity_rejected' => $value['rejected_quantity'],
                        'quantity_accepted' => DB::raw("quantity - {$value['rejected_quantity']}")
                    ]);
            }
            
            DocumentDetail::whereNotIn('id',$detailsRejected)
                ->where('document_id', '=', $params->document_id)
                ->update([
                    'status_id'         => Status::findByCode('DOCUMENT_DETAIL','ACCEPTED')->id,
                    'quantity_accepted' => DB::raw('quantity'),
                    'quantity_rejected' => 0
                ]);
        }

        //Guardado de imagen
        $date = date('Ymd');
        $time = date('His');
        $path = storage_path("app/public/mobile_pics/{$date}");
        if(!file_exists($path)){
            mkdir($path,0775,true);
        }
        $picToSave = [];
        foreach ($pics as $key => $pic) {
            $name = "pic_{$document_code}_{$time}_".uniqid('', true).".jpg";
            $pic['pic']->move( $path , $name );
            $tempPicToSave = new DocumentAttachment;
            $tempPicToSave->path = "storage/mobile_pics/{$date}/{$name}";
            $tempPicToSave->type = "jpg";
            $tempPicToSave->class = $pic['class'];
            $picToSave[] = $tempPicToSave;
        }
        $document->documentAttachments()->saveMany($picToSave);
        //fin guardado imagen
        ReturnFilesToCompany::dispatch($document)->onQueue('files_return');

        return response()->json([
            'patente'    => 'ok'
        ],200);

    }


    public function routeInfo(Request $request){

        \Validator::make($request->all(), [
            'imei'    => 'required',
            'patente' => 'required',
            'type'    => 'required',
            'routes'  => 'required',
        ])->validate();

        $type = $request->input('type');
        $imei = $request->input('imei');
        $routes = $request->input('routes');
        $cellphone = Cellphone::findByImei($imei);

        $routesString = implode(",", $routes);
        //se actualizan rutas 
        $vehicle_code = $request->input('patente');

        $this->RouteChangeStatus($cellphone->office_id, $routesString);

        $date = date('Y-m-d H:i:s');
        if(count($routes) < 1){
            return response()->json(['error' => "Debe enviar las rutas"], 422);
        }
        
        RouteInfoProcess::dispatch($type, $cellphone->office_id, $routes, $date)->onQueue('route_info_process');

        return response()->json([
            'status'    => 'ok',
        ],200);
    }

    private function RouteChangeStatus($office_id, $routesString){
        $routeStatus = Status::findByCode('ROUTE','IN_DELIVERY')->id;
        $documentStatus = Status::findByCode('DOCUMENT','IN_DELIVERY')->id;
        $documentDetailStatus = Status::findByCode('DOCUMENT_DETAIL','IN_DELIVERY')->id;
        $routePending = Status::findByCode('ROUTE','PENDING_DEPARTURE')->id;

        DB::select("
            WITH _routes AS (
                    SELECT routes.id 
                    FROM routes
                    WHERE routes.code IN ({$routesString}) 
                    AND routes.office_id = {$office_id}
                    AND routes.status_id = {$routePending}
                ),
                _update_routes AS (
                    UPDATE routes 
                    SET status_id = {$routeStatus}, 
                        departure_date = now()  
                    WHERE id IN (SELECT id FROM _routes)
                    RETURNING routes.id
                ),
                _update_documents AS (
                    UPDATE documents 
                        SET status_id = {$documentStatus} 
                    WHERE route_id IN (SELECT id FROM _update_routes)
                    RETURNING documents.id 
                )
                UPDATE document_details SET status_id = {$documentDetailStatus} WHERE document_id IN (SELECT id FROM _update_documents);
        ");

    }
    public function tripInfo(Request $request){

        \Validator::make($request->all(), [
            'imei'        => 'required',
            'type'        => 'required',
            'customerRut' => 'required',
            'routes'      => 'required',
        ])->validate();


        $type = $request->input('type');
        $imei = $request->input('imei');
        $rut  = $request->input('customerRut');
        $date = $request->input('date');
        $routes = $request->input('routes');
        $routesString = implode(",", $routes);

        $cellphone = Cellphone::findByImei($imei);
        //evita que el no cambio de estatus rutas 
        $this->RouteChangeStatus($cellphone->office_id, $routesString);

        $currentCellphoneStatus = CurrentCellphoneStatus::where('cellphone_id', '=',$cellphone->id)->first();
        if($type == 'INI'){
            TripInfoProcess::dispatch($type, $currentCellphoneStatus->vehicle_id, $cellphone->office_id, $rut, $date)->onQueue('trip_info_process');
        } else {
            //llego cliente
        }


        return response()->json([
            'status'    => 'ok',
        ],200);
    }


    public function IncidenceInfo(Request $request){
        /*
        \Validator::make($request->all(), [
        'patente' => 'required'
        ])->validate();
        */

        return response()->json([
            'status'    => 'ok',
        ],200);
    }

}