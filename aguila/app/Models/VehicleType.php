<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\MaintainerModels;
use Cache;

class VehicleType extends Model
{
    Use MaintainerModels;
    protected $guarded = [];

    public static function findByCodeOrCreate($code, $label = null) {
    	return Cache::remember(
            "vehicle_type:{$code}", env('CACHE_TIME',30),
            function () use ($code, $label) {
                return self::firstOrCreate(
                    ['code' => $code], 
                    [
                        'label' => $label ?? 'tipo-'.$code,
                    ]
                );
            }
       	);
    }
}
