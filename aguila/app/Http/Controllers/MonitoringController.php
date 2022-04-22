<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CurrentGpsStatus;
use App\Models\Office;
use App\Models\CurrentCellphoneStatus;
use App\Models\GpsDevice;
use App\Models\VehicleCondition;

use DataTables;
use DB;
use  App\Http\Traits\DeviceTrait;

class MonitoringController extends Controller
{   
    use DeviceTrait;

    public function index(Request $request){

        $office_id           = $request->input('office_id',0);
        $type                = $request->input('type','0');
        $offices             = \Auth::user()->getOffices();
        $vehicleConditions   = VehicleCondition::get();
        $vehicleCondition_id = $request->input('vehicleCondition_id',0);

    	if(!$request->ajax()){
            return view('monitoring.index', [
                'offices'   => $offices, 
                'office_id' => $office_id, 
                'type'      => $type,
                'vehicleConditions' => $vehicleConditions,
                'vehicleCondition_id' => $vehicleCondition_id
            ]);
        }

        $officesSql = $office_id != 0 
            ? $office_id 
            : implode(',', $offices->pluck('id')->toArray());

        $conditionsSql = $vehicleCondition_id != 0 
            ? $vehicleCondition_id 
            : implode(',', $vehicleConditions->pluck('id')->toArray());
        
        $gps = CurrentGpsStatus::allInfo($officesSql, $conditionsSql);

        $cell = CurrentCellphoneStatus::allInfo($officesSql, $conditionsSql)
            ->union($gps);
        
        $data = DB::table(DB::raw("({$cell->toSql()}) as x"))
            ->when($type, function ($query) use ($type) {
                return $query->where('type', $type);
            })
            ->orderBYDesc('date_time');

        return DataTables::of($data)
        	->addColumn('type', function ($data) {
                return $data->type == 'gps' ? 'GPS' : 'TELEFONO';
            })
            ->editColumn('ignition_status', function ($data){
                return $data->ignition_status ? 'Encendido' : 'Apagado';
            })
            ->addColumn('action', function ($data) {
                return view('helpers.actionButtons',[
                    'show'       => ['map','document','shutdown-car'],
                    'id'         => $data->device_id, 
                    'vehicle_id' => $data->vehicle_id,
                    'gps_type'   => $data->gps_type
                ]);
            })->make(true);
    }

    /**
     * Trae la posicion de vehiculos y telefonos
     * para desplegarlos como marcadores en el mapa
     * @param Integer $office_id
     * @param String $type (gps, cell)
     * @param Integer $condition 
     * @return Json
     */
    public function markers($office_id, $type, $condition){

        $offices = \Auth::user()->getOffices();
        $vehicleConditions = VehicleCondition::get();

        $officesSql = $office_id != 0 
            ? $office_id 
            : implode(',', $offices->pluck('id')->toArray());

        $conditionsSql = $condition != 0 
            ? $condition 
            : implode(',', $vehicleConditions->pluck('id')->toArray());

        $gpsSql  = CurrentGpsStatus::markers($officesSql, $conditionsSql);
        $cellSql = CurrentCellphoneStatus::markers($officesSql, $conditionsSql);

        $sql = in_array($type, ['gps','cell'])
            ?  ${$type."Sql"}
            : "{$gpsSql} UNION {$cellSql}";
        
        $positions = DB::select($sql);

        return response()->json($positions);
    }

    /**
     * Busca informacion de un vehiculo en determinada oficina
     * dicha info se muestra en los infowindows de marcadores
     * @param String $type (gps, cell)
     * @param Integer $id corresponde al id del vehiculo
     * @param Integer $office_id corresponde al id de la oficina
     * @return view 
     */
    public function currentStatus($type, $id, $office_id){
        $offices = \Auth::user()->getOffices();
       
        $offices = $office_id != 0 
            ? [$office_id]
            : $offices->pluck('id')->toArray();        
        
        return view('monitoring.infowindows',[
            'data' => $type == 'gps' 
                ? CurrentGpsStatus::getInfo($offices, $id) 
                : CurrentCellphoneStatus::getInfo($offices, $id)
            ]
        );
    }

    
}
