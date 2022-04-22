<?php

namespace App\Http\Controllers\Maintainer\Vehicle;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;
use App\Models\Office;
use App\Models\Carrier;
use App\Models\VehicleType;
use App\Models\VehicleCondition;
use App\Models\Status;

class VehicleController extends Controller
{
    use MaintainerControllers;

    protected $view			= 'maintainer.vehicle.vehicle';
    protected $model 		= 'App\Models\Vehicle';
    protected $storeExcept  = [];
    protected $updateExcept = [];
    protected $avoidValidate = ['label', 'code', 'plate_number'];

    //@override
    protected function dataIndex()
    {
       return  $this->model::query()
        ->join('vehicle_types','vehicle_types.id', '=', 'vehicles.vehicle_type_id')
        ->join('vehicle_conditions','vehicle_conditions.id', '=', 'vehicles.vehicle_condition_id')
        ->join('carriers','carriers.id', '=', 'vehicles.carrier_id')
        ->join('offices','offices.id', '=', 'vehicles.office_id')
        ->select([
            'vehicles.id',
            'vehicles.code',
            'vehicles.label',
            'vehicles.plate_number',
            'vehicle_conditions.label as condition',
            'carriers.label AS carrier',
            'vehicle_types.label AS type',
            'offices.label AS office',
            'vehicles.office_id',
            'vehicles.created_at',
            'vehicles.updated_at',
        ]);
    }

    //@override
    protected function validationRules($request = null){

        return [
            'code'    			   => 'required|string|max:255|unique:vehicles',
            'label'   			   => 'required|string|max:255|unique:vehicles',
            'plate_number' 		   => 'required|string|max:255|unique:vehicles',
            'vehicle_type_id'	   => 'required|integer',
            'carrier_id'           => 'required|integer',
            'office_id' 		   => 'required|integer',
            'status_id'            => 'required|integer',
            'vehicle_condition_id' => 'required|integer',

        ];
    }

    //@override
    protected function createEditData($data = null){
   
        return [
            'offices'           => Office::get(),
            'carriers'          => Carrier::get(),
            'vehicleTypes'      => VehicleType::get(),
            'vehicleCondition'  => VehicleCondition::get(),
            'statusVehicle'     => Status::getByArea('VEHICLE'),
        ];
    }
}
