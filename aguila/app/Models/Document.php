<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
	protected $guarded = [];
	public $details;
    public function documentDetails()
    {
        return $this->hasMany('App\Models\DocumentDetail');
    }

    public function documentAttachments()
    {
        return $this->hasMany('App\Models\DocumentAttachment');
    }

    public function route()
    {
        return $this->belongsTo('App\Models\Route');
    }

    public function customerBranch()
    {
        return $this->belongsTo('App\Models\CustomerBranch');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }
}
