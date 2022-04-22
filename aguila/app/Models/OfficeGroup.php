<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\MaintainerModels;

class OfficeGroup extends Model
{
    Use MaintainerModels;
	protected $guarded = [];

    //this relation pass by office_office_group
    public function offices()
    {
    	return $this->belongsToMany('App\Models\Office', 'office_office_groups', 'office_group_id', 'office_id')->withTimestamps();
    }
}
