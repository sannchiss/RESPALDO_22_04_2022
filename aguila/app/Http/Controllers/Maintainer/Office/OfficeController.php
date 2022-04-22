<?php

namespace App\Http\Controllers\Maintainer\Office;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;

class OfficeController extends Controller
{
    use MaintainerControllers;

	protected $view			 = 'maintainer.office.office';
    protected $model 		 = 'App\Models\Office';
    protected $storeExcept   = [];
    protected $updateExcept  = [];
    protected $avoidValidate = ['label', 'code'];

    //@override
    protected function validationRules($request = null){
        return [
            'code'  => 'required|string|max:255|unique:offices',
            'label' => 'required|string|max:255|unique:offices',
        ];
    }
}
