<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentasUsuarios extends Model
{
    protected $table = 'cuentas_usuarios';

    protected $fillable  = ['id_usuario','empresa','cuenta_txa','cuenta_gts'];
  

}
