<?php

namespace App\Http\Controllers\Api\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CurrentCellphoneStatus;
use App\Models\Cellphone;
use App\Models\Employee;
use App\Models\Vehicle;

class CellphoneStatusController extends Controller
{
    public function getLocation(Request $request){
        \Validator::make($request->all(), [
            'latitude' 	   => 'required',
            'longitude'    => 'required',
            'imei'		   => 'required',
            'plate_number' => 'required',
            'driver_code'  => 'required',
        ])->validate();

        $imei 		 = $request->input('imei');
        $codeDriver  = $request->input('driver_code');
        $plateNumber = $request->input('plate_number');

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        try {

         	$cellphone = Cellphone::findByImei($imei);
         	$driver = Employee::findByOfficeTypeCode($cellphone->office_id, 'DRIVER', $codeDriver);
         	$vehicle = Vehicle::findByCodeAndUpdateOffice($plateNumber, $cellphone->office_id);
            CurrentCellphoneStatus::UpdateOrCreate(
                ['cellphone_id' => $cellphone->id],
                [
                    'vehicle_id'      => $vehicle->id ?? null,
                    'employee_id'     => $driver->id ?? null,
                    'lat'             => $latitude,
                    'lon'             => $longitude,
                    'date_time'       => date('Y-m-d H:i:s'),
                ]
            );
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            \Log::info("Some error ocurred processing cellphone data");
        }
        

        return response()->json([
            'result'    => 'ok',
        ],200);

    }
}
