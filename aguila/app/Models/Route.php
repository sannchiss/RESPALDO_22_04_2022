<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $guarded = [];

    public function documents()
    {
        return $this->hasMany('App\Models\Documents');
    }

    public function office()
    {
        return $this->belongsTo('App\Models\Office');
    }
}
