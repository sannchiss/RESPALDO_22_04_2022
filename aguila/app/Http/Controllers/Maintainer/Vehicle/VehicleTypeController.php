<?php

namespace App\Http\Controllers\Maintainer\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;

class VehicleTypeController extends Controller
{
     use MaintainerControllers;

	protected $view			 = 'maintainer.vehicle.type';
    protected $model 		 = 'App\Models\VehicleType';
    protected $storeExcept   = [];
    protected $updateExcept  = [];
    protected $avoidValidate = ['label', 'code'];

    //@override
    protected function validationRules($request = null){

        return [
            'code'    => 'required|string|max:255|unique:vehicle_types',
            'label'   => 'required|string|max:255|unique:vehicle_types',
        ];

    }
}
