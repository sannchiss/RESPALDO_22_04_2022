<?php

namespace App\Http\Controllers\Maintainer\Security;

use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;
use Illuminate\Http\Request;
use Validator;

class PermissionController extends Controller
{
	//Funciones por defecto del controllador
	use MaintainerControllers; 
    protected $view = 'maintainer.security.permission';
    protected $model = 'App\Models\Permission';
    protected $storeExcept = [];
    protected $updateExcept = [];
    protected $avoidValidate = ['label', 'code','root_route'];

    #override
    protected function validationRules($request = null){
        return [
            'code'        => 'required|string|max:255|unique:permissions',
            'label'       => 'required|string|max:255|unique:permissions',
            //'root_route'  => 'required|string|max:255|unique:permissions',
        ];
    }

    //Metodo para guardar la edicion de la vista edit
    public function update(Request $request, $id)
    {
        $model          = $this->model::find($id);
        $validateFlieds = $this->avoidValidationRules($model, $request);

        Validator::make($request->all(), $validateFlieds )->validate();
        
        $toUpdate = $request->except($this->updateExcept);
        $toUpdate['is_credential'] =  $request->input('is_credential', false);

        $data = $model->update($toUpdate);
        
    }
}
