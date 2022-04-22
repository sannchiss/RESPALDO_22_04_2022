<?php

namespace App\Http\Controllers\Maintainer\Device\Gps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;
use App\Models\GpsType;
use App\Models\PhoneOperator;
use App\Models\Status;
use App\Models\Vehicle;

class GpsDevicesController extends Controller
{
    use MaintainerControllers;

    protected $view			 = 'maintainer.device.gps.device';
    protected $model 		 = 'App\Models\GpsDevice';
    protected $storeExcept   = [];
    protected $updateExcept  = [];
    protected $avoidValidate = ['imei','imsi'];

    //@override
    protected function validationRules($request = null){
        return [
            'imei'        		=> 'required|string|max:255|unique:gps_devices',
            'imsi'        		=> 'required|string|max:255|unique:gps_devices',
            'phone_operator_id' => 'required|integer',
            'phone_number' 		=> 'required|integer',
            'gps_type_id' 		=> 'required|integer',
        ];
    }

    //@override
    protected function dataIndex()
    {
       return  $this->model::query()
        ->join('gps_types','gps_types.id', '=', 'gps_devices.gps_type_id')
        ->join('phone_operators','phone_operators.id','=','gps_devices.phone_operator_id')
        ->join('statuses','statuses.id', '=','gps_devices.status_id')
        ->leftjoin('vehicles','vehicles.id', '=','gps_devices.vehicle_id')
        ->select([
            'gps_devices.id',
            'gps_devices.imei',
            'gps_devices.imsi',
            'gps_devices.version',
            'gps_types.label as type',
            'statuses.label as status',
            'vehicles.label as vehicle',
            'gps_devices.phone_number',
            'phone_operators.label AS phone_operator',
            'gps_devices.created_at',
            'gps_devices.updated_at',
        ]);
    }

    //@override
    protected function showData($id){
        return  $this->model::join('gps_types','gps_types.id', '=', 'gps_devices.gps_type_id')
				        ->join('phone_operators','phone_operators.id','=','gps_devices.phone_operator_id')
				        ->join('statuses','statuses.id', '=','gps_devices.status_id')
				        ->leftjoin('vehicles','vehicles.id', '=','gps_devices.vehicle_id')
                        ->where([
                            ['gps_devices.id', '=', $id]
                        ])
                        ->select([
                            \DB::raw('gps_devices.*'),
                            'gps_types.label as type',
                            'phone_operators.label AS phone_operator',
                            'statuses.label as status',
                            'vehicles.label as vehicle',
                        ])
                        ->first();
    }

    //@override
    protected function createEditData($data = null){
        return [
            'gpsTypes'		 => GpsType::get(),
            'phoneOperators' => PhoneOperator::get(),
            'statuses'       => Status::getByArea('GPS_DEVICE'),
            'vehicles'		 => Vehicle::leftJoin('gps_devices', 'gps_devices.vehicle_id', 'vehicles.id')
                                ->whereNull('gps_devices.imei')
                                ->select('vehicles.id','vehicles.label')
                                ->get(),
        ];
    }
}
