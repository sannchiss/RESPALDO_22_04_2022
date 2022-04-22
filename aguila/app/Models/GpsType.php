<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\MaintainerModels;

class GpsType extends Model
{
	Use MaintainerModels;
    protected $guarded = [];
}
