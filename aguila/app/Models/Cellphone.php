<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\MaintainerModels;
use Cache;
use App\Models\PhoneOperator;
use App\Models\CellphonePlatform;

class Cellphone extends Model
{
    Use MaintainerModels;
    protected $guarded = [];

    public static function findByCodeOrCreate($imei,$imsi,$phoneNumber,$operatorName,$phoneModel,$phoneBrand,$phoneOs,$osVersion,$officeCode, $officeLabel) {
    	return Cache::remember(
            "cellphone:{$imei}", env('CACHE_TIME',30),
            function () use ($imei,$imsi,$phoneNumber,$operatorName,$phoneModel,$phoneBrand,$phoneOs,$osVersion,$officeCode, $officeLabel) {
                return self::firstOrCreate(
                    ['imei' => $imei],
                    [
                    	'label' => $imei,
                    	'imsi' => $imsi,
                    	'phone_number' => $phoneNumber ?? $imei,
                    	'phone_operator_id' => PhoneOperator::findByCodeOrCreate($operatorName)->id,
                    	'cellphone_platform_id' => CellphonePlatform::findByCodeOrCreate($phoneOs)->id,
                    	'os_version' => $osVersion,
                    	'office_id'  => Office::findByCodeOrCreate($officeCode, $officeLabel)->id,
                    	'status_id'  => Status::findByCode('CELLPHONE','ACTIVE')->id,
                    	'employee_id' => null,
                    ]
                );
            }
       	);
    }

    public static function findByImei($imei){
        $cellphone = Cache::get("cellphone:{$imei}");
        if(is_null($cellphone)){
            $cellphone = self::where('imei', '=', $imei)->first();
            if(!is_null($cellphone)){
                Cache::put("cellphone:{$imei}", $cellphone, env('CACHE_TIME',30));
            }
        }
        return $cellphone;
    }

}
