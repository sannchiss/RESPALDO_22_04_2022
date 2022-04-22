<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class CurrentCellphoneStatus extends Model
{
    protected $guarded = [];

    public static function getInfo($offices, $id) {
        return self::query()
            ->join('cellphones', function($join) use($offices){
                $join->on('cellphones.id', '=', 'current_cellphone_statuses.cellphone_id')
                    ->whereIn('cellphones.office_id', $offices);
            })
            ->leftJoin('employees', 'employees.id','=','current_cellphone_statuses.employee_id')
            ->leftJoin('vehicles','vehicles.id', '=', 'current_cellphone_statuses.vehicle_id')
            ->select([
                DB::raw("'cell' AS type"),
                'vehicles.id',
                'vehicles.label',
                'cellphones.label as code_cell',
                'cellphones.phone_number',
                DB::raw("CONCAT(employees.name,' ',employees.lastname) AS fullname"),
                'current_cellphone_statuses.speed',
                DB::raw("null AS heading"),
                'current_cellphone_statuses.date_time',
                DB::raw("'0' AS ignition_status"),
                DB::raw("(
                    CASE WHEN DATE_PART('minute', now() - current_cellphone_statuses.date_time)  <= 10 
                        THEN 'success' 
                    WHEN DATE_PART('minute', now() - current_cellphone_statuses.date_time)  > 10 
                    AND DATE_PART('minute', now() - current_cellphone_statuses.date_time) <= 20 
                        THEN 'warning' 
                    ELSE 'danger' END) AS condition ")
            ])
            ->where('current_cellphone_statuses.cellphone_id','=', $id)
            ->first();
    }

    /**
     * @param String $offices listado de ids de oficinas en string 
     * @param String $conditions listado de ids de condiciones en string 
     * @return String 
     */
    public static function markers($offices, $conditions){
        return "SELECT 
            CONCAT('C',current_cellphone_statuses.cellphone_id) AS device_id,
            cellphones.label,
            cellphones.id,
            current_cellphone_statuses.lat, 
            current_cellphone_statuses.lon,
            current_cellphone_statuses.speed,
            current_cellphone_statuses.date_time,
            'cell' AS type,
            (CASE WHEN DATE_PART('minute', now() - current_cellphone_statuses.date_time)  <= 10 
                THEN 'success' 
            WHEN DATE_PART('minute', now() - current_cellphone_statuses.date_time)  > 10
            AND DATE_PART('minute', now() - current_cellphone_statuses.date_time) <= 20 
                THEN 'warning' 
            ELSE 'danger' END) AS condition
        FROM current_cellphone_statuses
        JOIN cellphones ON cellphones.id = current_cellphone_statuses.cellphone_id 
            AND cellphones.office_id IN ({$offices})  
        JOIN vehicles ON vehicles.id = current_cellphone_statuses.vehicle_id
            AND vehicles.office_id IN ({$offices}) AND vehicles.vehicle_condition_id IN ({$conditions})
        ";
    }


    public static function allInfo($offices, $conditions){
        return self::query()
        ->join('cellphones', function($join) use($offices){
            $join->on('cellphones.id', '=', 'current_cellphone_statuses.cellphone_id')
                    ->whereRaw("cellphones.office_id IN ({$offices})");
        })
        ->leftJoin('employees', 'employees.id','=','current_cellphone_statuses.employee_id')
        ->leftJoin('vehicles','vehicles.id', '=', 'current_cellphone_statuses.vehicle_id')
        ->leftJoin('vehicle_conditions','vehicle_conditions.id', '=', 'vehicles.vehicle_condition_id')
        ->whereRaw("vehicles.vehicle_condition_id IN ({$conditions})")
        ->select([
            DB::raw("'cell' AS type"),
            DB::raw("CONCAT('C',current_cellphone_statuses.cellphone_id) as device_id"),
            'cellphones.label as code',
            'vehicles.id AS vehicle_id',
            'vehicles.label',
            'vehicle_conditions.label AS vehicle_conditions',
            DB::raw("CONCAT(employees.name,' ',employees.lastname) as fullname"),
            'current_cellphone_statuses.speed',
            'current_cellphone_statuses.date_time',
            DB::raw("'0' AS ignition_status"),
            DB::raw("'CELL' AS gps_type")
        ]);

    }

}
