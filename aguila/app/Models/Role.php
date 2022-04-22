<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\MaintainerModels;


class Role extends Model
{
	Use MaintainerModels;
    protected $guarded = [];

    public function rolePermissions()
    {
        return $this->hasMany('App\Models\RolePermission');
    }

    //this relation pass by role_permission
    public function permissions()
    {
    	return $this->belongsToMany('App\Models\Permission', 'role_permissions', 'role_id', 'permission_id')->withTimestamps();
    }

    public function offices()
    {
        return $this->belongsToMany('App\Models\Office', 'role_offices', 'role_id', 'office_id')->withTimestamps();
    }

}
