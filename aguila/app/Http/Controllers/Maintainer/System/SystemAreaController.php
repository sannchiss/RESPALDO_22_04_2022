<?php

namespace App\Http\Controllers\Maintainer\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;

class SystemAreaController extends Controller
{
    use MaintainerControllers;

	protected $view			 = 'maintainer.system.area';
    protected $model 		 = 'App\Models\SystemArea';
    protected $storeExcept   = [];
    protected $updateExcept  = [];
    protected $avoidValidate = ['label', 'code'];

    //@override
    protected function validationRules($request = null){
        return [
            'code'  => 'required|string|max:255|unique:system_areas',
            'label' => 'required|string|max:255|unique:system_areas',
        ];
    }
}
