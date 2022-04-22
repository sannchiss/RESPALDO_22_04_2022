<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\MaintainerModels;
use Cache;

class Employee extends Model
{
	Use MaintainerModels;
	protected $guarded = [];

	
    public function getFullNameAttribute(){
    	return $this->name." ".$this->lastname;
    }

    public function employeeType()
    {
        return $this->belongsTo('App\Models\EmployeeType');
    }

    public static function findByCodeOrCreate($code, $type, $office_id, $name, $lastname = null, $rut = null, $dv = null){
        $rut = $rut ?? $code;
        $dv  = $dv ?? '-';
        return Cache::remember(
            "employee:{$office_id}:{$type}:{$code}", env('CACHE_TIME',30),
            function () use ($code, $type, $office_id, $name, $lastname, $rut,$dv) {
                if(is_null($lastname)){
                    $splitedName = self::splitName($name);
                    $name      = $splitedName['name'];
                    $lastname  = $splitedName['lastname'];
                }
                $employee_type_id = EmployeeType::findByCodeOrCreate($type)->id;
                return self::firstOrCreate(
                    [
                        'code'             => $code, 
                        'employee_type_id' => $employee_type_id,
                        'office_id'        => $office_id,
                    ], 
                    [
                        'rut'              => $rut,
                        'dv'               => $dv,
                        'status_id'        => Status::findByCode('EMPLOYEE','ACTIVE')->id,
                        'name'             => $name,
                        'lastname'         => $lastname,
                        'phone'            => 0,
                    ]
                );
            }
        );
    }

    public static function findByOfficeTypeCode($office_id, $type, $code){
        $employee = Cache::get("employee:{$office_id}:{$type}:{$code}");

        if(is_null($employee)){

            $employee_type_id = EmployeeType::findByCodeOrCreate($type)->id;
            $employee = self::where([
                ['office_id', '=', $office_id],
                ['employee_type_id', '=', $employee_type_id],
                ['code', '=', $code],
            ])->first();

            if(!is_null($employee)){
                Cache::put("employee:{$office_id}:{$type}:{$code}", $employee, env('CACHE_TIME',30));
            }
        }
        return $employee;
    }

    private static function splitName($name){
        $parts    = explode(" ", $name);
        $numParts = count($parts);
        $lastname = '';

        if($numParts <= 3){
            $name  = $parts[0];
            $forInit = 1;

        } else {
            $name  = $parts[0].' '.$parts[1];
            $forInit = 2;
        }

        for ($i=$forInit; $i < $numParts; $i++) { 
            $lastname .= ' '.$parts[$i];
        }

        if($lastname == '') {
            $lastname = '-';
        }

        return ['name' => $name, 'lastname' => ltrim($lastname)];
    }
}
