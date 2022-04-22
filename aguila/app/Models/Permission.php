<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\MaintainerModels;

/*
* Se implementa chequeo por bitwise para los subpermisos (ver, editar, crear, eliminar)
* doc -> http://sforsuresh.in/implemention-of-user-permission-with-php-mysql-bitwise-operators/
*/
class Permission extends Model
{
	Use MaintainerModels;
	
    protected $guarded = [];

    const SUBPERMISSIONS = [
			'SHOW' 	 	 => ['val' => 1,  'route' => ['show']],
			'UPDATE' 	 => ['val' => 2,  'route' => ['edit', 'update']],
			'CREATE' 	 => ['val' => 4,  'route' => ['create', 'store']],
			'DELETE' 	 => ['val' => 8,  'route' => ['destroy']],
			'EXPORT_PDF' => ['val' => 16, 'route' => ['pdf']],
			'EXPORT_XLS' => ['val' => 32, 'route' => ['xls']],
		];


    public function setPermissionsAttribute(Array $value){
		$this->attributes['permissions'] =  array_sum($value);
	}

	public function getPermissionsAttribute(){
		$permissions = self::SUBPERMISSIONS;
		$selfPermission = isset($this->attributes['permissions']) ? $this->attributes['permissions'] : 0;
		foreach (self::SUBPERMISSIONS as $key => $value) {
			$permissions[$key]['checked'] = ($value['val'] & $selfPermission) > 0 ? 'checked' : '';
		}

		return $permissions;
	}

	public static function hasPermission(String $route = '', $isDirectRoute = false) {
		
		$permissions = RolePermission::getUserPermission(\Auth::user()->role_id);
		if($isDirectRoute ===  false){
			$method = $route;
			$route  = \Request::route()->getName();
	    	$route  = explode('.', (string) $route, -1);
	    	$route  = implode('.', $route);
	    	$route  .= ".".$method;
	    }
	    
	    if(in_array($route, $permissions)){
			return true;
		}

		return false;
	}

}

