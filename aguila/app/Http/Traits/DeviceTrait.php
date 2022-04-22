<?php
namespace App\Http\Traits;

use DB;
Use Redis;
use Validator;
use Illuminate\Http\Request;

trait DeviceTrait{

    public function engineOffOn(Request $request){
        Validator::make($request->all(), [
            'device_id'  => 'required|integer'
        ])->validate();
        
        $gpsDevice = $this->deviceStatus($request->device_id);
        
        list($cmd, $msg) = $gpsDevice->ignition_status 
            ? ["00000000000000140C01050000000C7365746469676F7574203F30010000EDB5", "INHABILITADO"]
            : ["00000000000000140C01050000000C7365746469676F7574203F310100007DB4", "HABILITADO"];

        Redis::set('gps_'.$gpsDevice->imei, json_encode([
            "hasSended" => false, 
            "datetime"  => time() * 1000,
            "command"   => $cmd
        ]));

        return response()->json([
            'status'  => 'OK',
            'message' => "Motor de vehiculo {$msg}"
        ], 200);
    }


    protected function deviceStatus($device_id){
        return  DB::selectOne("
            SELECT 
                gps_devices.imei,
                current_gps_statuses.lat, 
                current_gps_statuses.lon,
                current_gps_statuses.heading,
                current_gps_statuses.speed,
                current_gps_statuses.date_time,
                case when current_gps_statuses.ignition_status = 0 then false else true end as ignition_status
            FROM gps_devices
            JOIN current_gps_statuses ON current_gps_statuses.gps_device_id = gps_devices.id
            WHERE gps_devices.id = {$device_id}
        ");
    }

    public function currentStatus(Request $request){
        Validator::make($request->all(), [
            'device_id'  => 'required|integer'
        ])->validate();

        return response()->json([
            'status' => 'OK',
            'data'   => $this->deviceStatus($request->device_id)
        ], 200);

    }


    
}