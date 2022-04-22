<?php

namespace App\Http\Controllers\Maintainer\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;
use App\Models\SystemArea;
use App\Models\Status;

class StatusReasonController extends Controller
{
    use MaintainerControllers;

	protected $view			 = 'maintainer.system.status_reason';
    protected $model 		 = 'App\Models\StatusReason';
    protected $storeExcept   = ['system_area_id'];
    protected $updateExcept  = ['system_area_id'];
    protected $avoidValidate = [];

    //@override
    protected function validationRules($request = null){
    	$system_area_id = $request->input('system_area_id');
        $code 			= $request->input('code');

        return [
            'label' => 'required|string|max:255',
            'status_id' => 'required|integer'
        ];
    }

     //@override
    protected function dataIndex()
    {
       return  $this->model::query()
        ->join('statuses','statuses.id', '=', 'status_reasons.status_id')
        ->join('system_areas','system_areas.id', '=', 'statuses.system_area_id')
        ->select([
            'status_reasons.id',
            'status_reasons.label',
            'statuses.label as status',
            'system_areas.label as area',
            'status_reasons.created_at',
            'status_reasons.updated_at'
        ])
        ->orderBy('system_areas.label','statuses.label');
    }

    //@override
    protected function showData($id){
        return  $this->model::join('statuses','statuses.id', '=', 'status_reasons.status_id')
            ->join('system_areas','system_areas.id', '=', 'statuses.system_area_id')
            ->select([
                'status_reasons.id',
				'status_reasons.label',
				'status_reasons.color',
				'statuses.label as status',
				'system_areas.label as area',
            ])
            ->first();
    }

    //@override
    protected function createEditData($data = null){

        $system_area_id = $data->status->system_area_id ?? null;
        $statuses       = is_null($system_area_id) ? null : Status::where('system_area_id', '=', $system_area_id)->get();
        return [
            'systemAreas' => SystemArea::get(),
            'statuses'    => $statuses
        ];
    }
}
