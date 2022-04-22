<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuentas extends Model
{
    //
    protected $table = 'cuentas';

    protected $fillable  = ['cuenta_txa','cuenta_gts','empresa'];



}
