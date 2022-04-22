<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Traits\MaintainerModels;
use App\Models\Office;
use DB;
class User extends Authenticatable
{
    use Notifiable;
    use MaintainerModels;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password','employee_id','role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }

    public function getOffices(){
        $role_id = $this->role_id;
        return Office::join('role_offices',
            function($join) use ($role_id) {
                $join->on('role_offices.office_id','=','offices.id')
                    ->on('role_offices.role_id','=',DB::raw($role_id));
            })
            ->select([
                'offices.id',
                'offices.label'
            ])->get();
    }
}
