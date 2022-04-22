<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\MaintainerModels;

class StatusReason extends Model
{
    Use MaintainerModels;
	protected $guarded = [];

	public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }
}
