<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\MaintainerModels;
use Cache;
class Vehicle extends Model
{
	use MaintainerModels;
    protected $guarded = [];

    public static function findByCodeOrCreate($code, $plate, $label, $carrier_id = null, $office_id = null, $employee_id = null){
    	$vehicle = Cache::get("vehicles:{$code}");
        $office      = Office::findByCodeOrCreate('DEFAULT','No definido');
        $carrier     = Carrier::findByCodeOrCreate('DEFAULT', 'No definido');
        $vehicleType = VehicleType::findByCodeOrCreate('DEFAULT','No definido');
        //si no lo encuentro lo busco y lo creo si no lo encuentra
        if(is_null($vehicle)){
            $vehicle =  self::firstOrCreate(
                ['code' => $code], 
                [
                    'label'             => $label,
                    'plate_number'      => $plate,
                    'vehicle_type_id'   => $vehicleType->id,
                    'carrier_id'        => $carrier_id ?? $carrier->id,
                    'office_id'         => $office_id ?? $office->id,
                    'status_id'         => Status::findByCode('VEHICLE','ACTIVE')->id,
                    'employee_id'       => $employee_id,
                ]
            );
            if(!is_null($vehicle)){
                if(!is_null($office_id) && $vehicle->office_id != $office_id){
                    $vehicle->office_id = $office_id;
                    $vehicle->save();
                }
            }


        }else{
            //actualizo si el empleado es diferente or office
            if(!is_null($employee_id) && ($vehicle->employee_id != $employee_id || $vehicle->office_id != $office_id) ) {

                $vehicle = self::updateOrCreate(
                    ['code' => $code], 
                    [
                        'label'             => $vehicle->label ?? $label,
                        'plate_number'      => $vehicle->plate_number ?? $plate,
                        'vehicle_type_id'   => $vehicle->vehicle_type_id ?? $vehicleType->id,
                        'carrier_id'        => $vehicle->carrier_id ?? $carrier_id ?? $carrier->id,
                        'office_id'         => $office_id ?? $vehicle->office_id ?? $office->id,
                        'status_id'         => Status::findByCode('VEHICLE','ACTIVE')->id,
                        'employee_id'       => $employee_id,
                    ]
                );
            }
        }
        Cache::put("vehicles:{$code}", $vehicle, env('CACHE_TIME',30));
        return $vehicle;
    }

    public static function findByCodeAndUpdateOffice($code, $office_id = null){
        $vehicle = Cache::get("vehicles:{$code}");
         if(is_null($vehicle)){
            $vehicle = self::where('code', '=', $code)->first();
            if(!is_null($vehicle)){

                if($vehicle->office_id != $office_id && !is_null($office_id)){
                    $vehicle->office_id = $office_id;
                    $vehicle->save();
                }

                Cache::put("vehicles:{$code}", $vehicle, env('CACHE_TIME',30));
            }
        }

        return $vehicle;
    }

}
