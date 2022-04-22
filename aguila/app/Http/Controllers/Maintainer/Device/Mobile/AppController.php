<?php

namespace App\Http\Controllers\Maintainer\Device\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;
use App\Models\CellphonePlatform;
use DB;
use Cache;

class AppController extends Controller
{
    use MaintainerControllers;

	protected $view			= 'maintainer.device.mobile.app';
    protected $model 		= 'App\Models\App';
    protected $storeExcept  = [];
    protected $updateExcept = [];
    protected $avoidValidate = ['code'];

    //@override
    protected function validationRules($request = null){
        return [
            'code'        			=> 'required|string|max:255|unique:apps',
            'label'       			=> 'required|string|max:255',
            'cellphone_platform_id' => 'required|integer',
            'latest_version_name'   => 'required|string',
            'previus_version_name'  => 'required|string',
            'latest_version_code'   => 'required|integer',
            'previus_version_code'  => 'required|integer',
            'active_update'			=> 'required|boolean'
        ];
    }

    //@override
    protected function dataIndex()
    {
       return  $this->model::query()
        ->join('cellphone_platforms','cellphone_platforms.id', '=', 'apps.cellphone_platform_id')
        ->select([
            'apps.id',
            'apps.code',
            'apps.label',
            'cellphone_platforms.label as cellphone_platform',
            'apps.latest_version_name',
            'apps.previus_version_name',
            DB::raw("CASE WHEN apps.active_update = true THEN 'Activada' ELSE 'Desactivada' END active_update"),
            'apps.created_at',
            'apps.updated_at',
        ]);
    }

    //@override
    protected function showData($id){
        return  $this->model::join('cellphone_platforms','cellphone_platforms.id', '=', 'apps.cellphone_platform_id')
        ->select([
            'apps.id',
            'apps.code',
            'apps.label',
            'apps.icon',
            'cellphone_platforms.label as cellphone_platform',
            'apps.latest_version_name',
            'apps.previus_version_name',
            'apps.latest_version_code',
            'apps.previus_version_code',
            DB::raw("CASE WHEN apps.active_update = true THEN 'Activada' ELSE 'Desactivada' END active_update"),
            'apps.created_at',
            'apps.updated_at',
        ])
        ->first();
    }

    //@override
    protected function createEditData($data = null){
    	$updateStatus = [
    		0 => (object) ['id' => 1, 'label' => 'Activada'],
    		1 => (object) ['id' => 0, 'label' => 'Desactivada']
    	];
        return [
            'cellphonePlatforms' => CellphonePlatform::get(),
            'updateStatus'		 => $updateStatus
        ];
    }

    protected function alterUpdate($data, $request){
        Cache::forget("app:{$data->code}");
    }

}
