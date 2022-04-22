<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\MaintainerModels;
use Cache;

class PhoneOperator extends Model
{
    Use MaintainerModels;
    protected $guarded = [];

    public static function findByCodeOrCreate($operatorName) {
    	return Cache::remember(
            "phone_operator:{$operatorName}", env('CACHE_TIME',30),
            function () use ($operatorName) {
                return self::firstOrCreate(
                    ['label' => $operatorName], 
                    [
                    	'code' => $operatorName,
                    ]
                );
            }
       	);
    }
}
