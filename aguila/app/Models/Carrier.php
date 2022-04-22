<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\MaintainerModels;
use Cache;

class Carrier extends Model
{
    Use MaintainerModels;
	protected $guarded = [];

	public static function findByCodeOrCreate($code, $label = '') {
    	return Cache::remember(
            "carrier:{$code}", env('CACHE_TIME',30),
            function () use ($code, $label) {
                return self::firstOrCreate(
                    ['code' => $code], 
                    [
                        'label' => $code.$label,
                        'other' => $label
                    ]
                );
            }
       	);
    }
}
