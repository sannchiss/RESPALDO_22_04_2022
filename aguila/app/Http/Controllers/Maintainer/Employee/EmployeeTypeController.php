<?php

namespace App\Http\Controllers\Maintainer\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;


class EmployeeTypeController extends Controller
{	
	use MaintainerControllers;

	protected $view			= 'maintainer.employee.type';
    protected $model 		= 'App\Models\EmployeeType';
    protected $storeExcept  = [];
    protected $updateExcept = [];
    protected $avoidValidate = ['label', 'code'];

    //@override
    protected function validationRules($request = null){
        return [
            'code'        => 'required|string|max:255|unique:employee_types',
            'label'       => 'required|string|max:255|unique:employee_types',
        ];
    }
}