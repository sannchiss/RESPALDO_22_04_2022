<?php

namespace App\Http\Controllers\Maintainer\Device\Gps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;

class GpsTypeController extends Controller
{
    use MaintainerControllers;

	protected $view			= 'maintainer.device.gps.type';
    protected $model 		= 'App\Models\GpsType';
    protected $storeExcept  = [];
    protected $updateExcept = [];
    protected $avoidValidate = ['label', 'code'];

    //@override
    protected function validationRules($request = null){
        return [
            'code'        => 'required|string|max:255|unique:gps_types',
            'label'       => 'required|string|max:255|unique:gps_types',
        ];
    }
}
