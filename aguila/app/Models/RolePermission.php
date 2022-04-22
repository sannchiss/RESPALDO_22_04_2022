<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
class RolePermission extends Model
{
	protected $guarded = [];

	public static function getUserPermission($roleId){

		$rolePermissions = Cache::get("permissions:{$roleId}");
		
		$rolePermissions = self::join('permissions', 
				'permissions.id', '=', 'role_permissions.permission_id'
			)
			->where('role_id', $roleId)
			->Select('permission_id AS id', 'permissions.root_route', 'permissions.permissions', 'permissions.is_credential')
			->get();
		
		$permissions 	 = [];
		$permittedRoutes = [];
		foreach ($rolePermissions as $rolePermission){
			if(in_array($rolePermission->id, $permissions)){
				continue;
			}
			$permissions[] = $rolePermission->id;
			//verifico si es credencial
			if($rolePermission->is_credential){
				//evito duplicidad
				//expando credenciales para cuando contenga []
				$startCred = strpos($rolePermission->root_route,'[');
				if($startCred != false){
					$toReplace = ["[","]"];
					$routesPart = substr($rolePermission->root_route, $startCred );
					$routesPart = str_replace($toReplace, "", $routesPart);
					$routesPart = explode(",",$routesPart);
					$basePart   = substr($rolePermission->root_route,0, $startCred);

					//se inserta a las permitidas cada una
					foreach ($routesPart as $routePart) {
						if(!in_array($basePart.$routePart, $permittedRoutes)){
							$permittedRoutes[] = $basePart.$routePart;
						}
					}

				}
				else{
					if(!in_array($rolePermission->root_route, $permittedRoutes)){
						$permittedRoutes[] = $rolePermission->root_route;
					}
				}
			}
			else {//Expand routes
				$permittedRoutes[] = $rolePermission->root_route.".index";
				foreach (Permission::SUBPERMISSIONS as $value){
					if( ($value['val'] & $rolePermission->permissions) > 0 ){
						//recorro las rutas de cada subpermiso (store, create ..etc)
						foreach ($value['route'] as  $subroute){
							$newRoute = $rolePermission->root_route.".".$subroute;
							if(!in_array($newRoute, $permittedRoutes)){
								$permittedRoutes[] = $newRoute;
							}
						}
					}
				}
			}
		}

		return $permittedRoutes;
	}

}