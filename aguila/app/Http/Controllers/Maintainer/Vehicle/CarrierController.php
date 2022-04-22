<?php

namespace App\Http\Controllers\Maintainer\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;

class CarrierController extends Controller
{
    use MaintainerControllers;

	protected $view			 = 'maintainer.vehicle.carrier';
    protected $model 		 = 'App\Models\Carrier';
    protected $storeExcept   = [];
    protected $updateExcept  = [];
    protected $avoidValidate = ['label', 'code'];

    //@override
    protected function validationRules($request = null){

        return [
            'code'    => 'required|string|max:255|unique:carriers',
            'label'   => 'required|string|max:255|unique:carriers',
        ];
    }

}