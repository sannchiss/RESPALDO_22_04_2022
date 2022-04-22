<?php

namespace App\Http\Controllers\Maintainer\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;
use App\Models\SystemArea;
use Illuminate\Validation\Rule;

class StatusController extends Controller
{
    use MaintainerControllers;

	protected $view			 = 'maintainer.system.status';
    protected $model 		 = 'App\Models\Status';
    protected $storeExcept   = [];
    protected $updateExcept  = [];
    protected $avoidValidate = ['code'];

    //@override
    protected function validationRules($request = null){
    	$system_area_id = $request->input('system_area_id');
        $code 			= $request->input('code');

        return [
            'code'  => ['required',
                Rule::unique('statuses')->where(function ($query) use($system_area_id, $code) {
                    return $query->where('system_area_id', $system_area_id)
                    ->where('code',$code);
                })],
            'label' => 'required|string|max:255',
            'system_area_id' => 'required|integer'
        ];
    }

     //@override
    protected function dataIndex()
    {
       return  $this->model::query()
        ->join('system_areas','system_areas.id', '=', 'statuses.system_area_id')
        ->select([
            'statuses.id',
            'statuses.code',
            'statuses.label',
            'system_areas.label as area',
            'statuses.created_at',
            'statuses.updated_at'
        ])
        ->orderBy('system_areas.label','desc')
        ->orderBy('statuses.id','desc');
    }

    //@override
    protected function showData($id){
        return  $this->model::join('system_areas','system_areas.id', '=', 'statuses.system_area_id')
                        ->select([
                            'statuses.id',
				            'statuses.code',
				            'statuses.label',
				            'statuses.color',
				            'system_areas.label as area',
                        ])
                        ->first();
    }

    //@override
    protected function createEditData($data = null){
        return [
            'systemAreas' => SystemArea::get()
        ];
    }


}
