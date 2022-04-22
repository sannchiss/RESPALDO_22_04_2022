<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;
use DB;

class CurrentGpsStatus extends Model
{
    protected $guarded = [];

    public static function getInfo($offices, $id) {
        $data = self::query()
            ->join('vehicles', function($join) use($offices){
                $join->on('vehicles.id', '=', 'current_gps_statuses.vehicle_id')
                    ->whereIn('vehicles.office_id', $offices);
            })
            ->leftJoin('employees', 'employees.id','=','current_gps_statuses.employee_id')
            ->leftJoin('cellphones','cellphones.employee_id', '=', 'employees.id')
            ->join('gps_devices','gps_devices.id', '=', 'current_gps_statuses.gps_device_id')
            ->join('gps_types','gps_types.id','=','gps_devices.gps_type_id')
            ->select([
                DB::raw("'gps' AS type"),
                'vehicles.id',
                'vehicles.label',
                'cellphones.phone_number',
                DB::raw("CONCAT(employees.name,' ',employees.lastname) as fullname"),
                'current_gps_statuses.speed',
                'current_gps_statuses.heading',
                'current_gps_statuses.date_time',
                'current_gps_statuses.ignition_status',
                'current_gps_statuses.gps_device_id',
                'gps_types.code AS gps_type',
                DB::raw("(
                    CASE WHEN DATE_PART('minute', now() - current_gps_statuses.date_time)  <= 10 
                        THEN 'success' 
                    WHEN DATE_PART('minute', now() - current_gps_statuses.date_time)  > 10 
                    AND DATE_PART('minute', now() - current_gps_statuses.date_time) <= 20 
                        THEN 'warning' 
                    ELSE 'danger' END) AS condition 
                ")
            ])
            ->where('vehicles.id','=', $id)
            ->first();

        $data->heading = self::getHumanHeading($data->heading);
        return $data;
    }

    protected static function getHumanHeading($degree){

        if( ($degree >= 330 && $degree <= 360) || ($degree >= 0 && $degree<= 30) ) {
            return 'Norte';
        }

        if($degree > 30 && $degree < 60) {
            return 'Nor-Oriente';
        }

        if($degree >= 60 && $degree <= 120) {
            return 'Oriente';
        }

        if($degree > 120 && $degree < 150) {
            return 'Sur-Oriente';
        }

        if($degree >= 150 && $degree <= 210) {
            return 'Sur';
        }

        if($degree > 210 && $degree < 240) {
            return 'Sur-Poniente';
        }

        if($degree >= 240 && $degree <= 300) {
            return 'Poniente';
        }

        if($degree > 300 && $degree < 330) {
            return 'Nor-Poniente';
        }
    }

    /**
     * @param String $offices listado de ids de oficinas en string 
     * @param String $conditions listado de ids de condiciones en string 
     * @return String 
     */
    public static function markers($offices, $conditions){
        return "SELECT 
            CONCAT('D',current_gps_statuses.gps_device_id) AS device_id,
            vehicles.label,
            vehicles.id,
            current_gps_statuses.lat, 
            current_gps_statuses.lon,
            current_gps_statuses.speed,
            current_gps_statuses.date_time,
            'gps' AS type,
            (CASE WHEN DATE_PART('minute', now() - current_gps_statuses.date_time)  <= 10 
                THEN 'success' 
            WHEN DATE_PART('minute', now() - current_gps_statuses.date_time)  > 10 
            AND DATE_PART('minute', now() - current_gps_statuses.date_time) <= 20 
                THEN 'warning' 
            ELSE 'danger' END) AS condition
        FROM current_gps_statuses
        JOIN vehicles ON vehicles.id = current_gps_statuses.vehicle_id
            AND vehicles.office_id IN ({$offices}) AND vehicles.vehicle_condition_id IN ({$conditions})
        ";
    }


    public static function allInfo($offices, $conditions){
        return self::query()
        ->join('vehicles', function($join) use($offices, $conditions){
            $join->on('vehicles.id', '=', 'current_gps_statuses.vehicle_id')
                ->whereRaw("vehicles.office_id IN ({$offices})")
                ->whereRaw("vehicles.vehicle_condition_id IN ({$conditions})");
        })
        ->join('gps_devices','gps_devices.id', '=', 'current_gps_statuses.gps_device_id')
        ->join('gps_types','gps_types.id','=','gps_devices.gps_type_id')
        ->leftJoin('vehicle_conditions','vehicle_conditions.id', '=', 'vehicles.vehicle_condition_id')
        ->leftJoin('employees', 'employees.id','=','current_gps_statuses.employee_id')
        ->select([
            DB::raw("'gps' AS type"),
            DB::raw("CONCAT('D',current_gps_statuses.gps_device_id) as device_id"),
            'gps_devices.imei as code',
            'vehicles.id AS vehicle_id',
            'vehicles.label',
            'vehicle_conditions.label AS vehicle_conditions',
            DB::raw("CONCAT(employees.name,' ',employees.lastname) as fullname"),
            'current_gps_statuses.speed',
            'current_gps_statuses.date_time',
            'current_gps_statuses.ignition_status',
            'gps_types.code AS gps_type'
        ]);
    }
}
