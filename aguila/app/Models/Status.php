<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Cache;
use App\Http\Traits\MaintainerModels;

class Status extends Model
{
    Use MaintainerModels;
    protected $guarded = [];
    
    public static function getByArea($area){
    	return self::join(
	    			'system_areas', 
	  				function ($join) use ($area) {
	            		$join->on('system_areas.id', '=', 'statuses.system_area_id')
	            			 	 ->on('system_areas.code', '=', DB::raw("'{$area}'"));
        			}
        		)
	    		->select(DB::raw('statuses.*'))
	    		->get();
    }

    public static function findByCode($area,$code) {
    	return Cache::remember(
            "statuses:{$area}:{$code}", env('CACHE_TIME',30),
            function () use ($code, $area) {
                return self::Join(
		    			'system_areas', 
		  				function ($join) use ($area) {
		            		$join->on('system_areas.id', '=', 'statuses.system_area_id')
		            			 	 ->on('system_areas.code', '=', DB::raw("'{$area}'"));
	        			}
        			)
                ->where('statuses.code','=',$code)
                ->select('statuses.*')
                ->first();
            }
       	);
    }

    public static function selectOption($id)
    {        
        return view('helpers.selectOptions',[
            'data'    => self::where('system_area_id', '=', $id)->get(),
            'id'      => 'id',
            'label'   => 'label',
            'compare' => null
            ])->render();

    }
}
