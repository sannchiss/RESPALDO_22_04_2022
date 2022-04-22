<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\MaintainerModels;
use Cache;
class GpsDevice extends Model
{
    Use MaintainerModels;
	protected $guarded = [];

    public static function findByImeiOrCreate($imei, $type, $vehicle_id) {
    	return Cache::remember(
            "gps_devices:{$imei}", env('CACHE_TIME',30),
            function () use ($imei, $type, $vehicle_id) {
                return self::firstOrCreate(
                    ['imei' => $imei], 
                    [
                        'imsi' 		   		=> $imei,
                        'phone_number' 		=> $imei,
                        'vehicle_id'        => $vehicle_id,
                        'version'	   		=> 'S/I',
                        'status_id'         => Status::findByCode('GPS_DEVICE','ACTIVE')->id,
                        'phone_operator_id' => PhoneOperator::where('code', '=', 'MOVISTAR')->value('id'),
                        'gps_type_id'  		=> GpsType::where('code', '=', $type)->value('id'),
                    ]
                );
            }
       	);
    }
}
