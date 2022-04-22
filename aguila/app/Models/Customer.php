<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Customer extends Model
{
    protected $guarded = [];

    public static function findByCodeOrCreate($code, $rut, $label, $office_id) {
    	return Cache::remember(
            "customer:{$office_id}:{$code}", env('CACHE_TIME',30),
            function () use ($code, $rut, $label, $office_id) {
                return self::firstOrCreate(
                    [
                        'code' => $code, 
                        'office_id' => $office_id,
                    ], 
                    [
                    	'rut'	=> $rut,
                        'label' => $label,
                    ]
                );
            }
       	);
    }
}
