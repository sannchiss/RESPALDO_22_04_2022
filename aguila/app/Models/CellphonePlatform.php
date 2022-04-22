<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\MaintainerModels;
use Cache;

class CellphonePlatform extends Model
{
    Use MaintainerModels;
    protected $guarded = [];

    public static function findByCodeOrCreate($phoneOs) {
    	return Cache::remember(
            "Cellphone_platform:{$phoneOs}", env('CACHE_TIME',30),
            function () use ($phoneOs) {
                return self::firstOrCreate(
                    ['label' => $phoneOs], 
                    [
                    	'code' => $phoneOs,
                    ]
                );
            }
       	);
    }
}
