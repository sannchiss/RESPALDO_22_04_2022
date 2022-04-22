<?php

namespace App\Http\Controllers\Maintainer\Security;

use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\Office;
use DB;


class RoleController extends Controller
{
	use MaintainerControllers;
	protected $view = 'maintainer.security.role';
    protected $model = 'App\Models\Role';
    protected $storeExcept = ['permissions','offices'];
    protected $updateExcept = ['permissions','offices'];
    protected $avoidValidate = ['label', 'code'];

    //@override
    protected function createEditData($data = null){
    	$idRole = is_null($data->id) ? -9999 : $data->id;

    	$permissions = Permission::leftJoin(
    		'role_permissions', 
  			function ($leftJoin) use ($idRole) {
            	$leftJoin->on('role_permissions.permission_id', '=', 'permissions.id')
            			 ->on('role_permissions.role_id', '=', DB::raw($idRole));
        	})
    		->select([
    			'permissions.id', 
    			'permissions.label',
                'permissions.permissions', 
    			DB::raw("(CASE WHEN role_id is null THEN '' ELSE 'checked' END) AS checked")
    		])
    		->get();

        $offices = Office::leftJoin(
            'role_offices', 
            function ($leftJoin) use ($idRole) {
                $leftJoin->on('role_offices.office_id', '=', 'offices.id')
                         ->on('role_offices.role_id', '=', DB::raw($idRole));
            })
            ->select([
                'offices.id', 
                'offices.label',
                DB::raw("(CASE WHEN role_id is null THEN '' ELSE 'checked' END) AS checked")
            ])
            ->get();

    	return ["permissions" => $permissions, "offices" => $offices];
    }

    //@override
    protected function validationRules($request = null){
        return [
            'code'        => 'required|string|max:255|unique:roles',
            'label'       => 'required|string|max:255|unique:roles',
            'permissions' => 'required|array',
            'offices'     => 'required|array',
        ];
    }

    //@override
    protected function alterStore($data, $request){
    	$permissions = $request->input('permissions',[]);
        $offices = $request->input('offices',[]);

    	$data->permissions()->sync($permissions);
        $data->offices()->sync($offices);
    }

    //@override
    protected function alterUpdate($data, $request){
        $permissions = $request->input('permissions',[]);
        $offices = $request->input('offices',[]);
        $data->permissions()->sync($permissions);
        $data->offices()->sync($offices);

        //TODO Borrar el codigo comentado
        /*
    	$roleId = $data->id;
    	$oldPermission_ids = $data->rolePermissions()->pluck('permission_id');
    	$permission_ids = $request->input('permissions.*.permission_id',[]);

    	RolePermission::where('role_id', $roleId)
    		->whereNotIn('permission_id', $permission_ids)
    		->delete();

    	$permission_ids = $request->input('permissions.*',[]);
    	$collection = collect($permission_ids)->whereNotIn('permission_id', $oldPermission_ids);

    	$permissions = $collection->map(function ($item, $key) use ($roleId) {
    		return array_merge($item, ['role_id' => $roleId]);
		});
		
    	$data->rolePermissions()->createMany($permissions->toArray());
        */

    }


}
