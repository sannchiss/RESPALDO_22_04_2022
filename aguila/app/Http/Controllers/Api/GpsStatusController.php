<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\GpsDevice;
use App\Models\CurrentGpsStatus;
use Validator;
use  App\Http\Traits\DeviceTrait;
use App\Jobs\GpsEvent;

class GpsStatusController extends Controller
{
    use DeviceTrait;

    public function store(Request $request){
    	\Validator::make($request->all(), [
            'data' => 'required'
        ])->validate();

        $datas = $request->input('data');
  
        //\Log::info('Recibiendo '.count($datas).' datos de posicion');
        foreach ($datas as $key => $data) {
            try {
                $vehicle   = Vehicle::findByCodeOrCreate($data['label'], $data['plate'],$data['label']);
                $gpsDevice = GpsDevice::findByImeiOrCreate($data['imei'], $data['type'], $vehicle->id);

                CurrentGpsStatus::UpdateOrCreate(
                    ['gps_device_id' => $gpsDevice->id],
                    [
                        'vehicle_id'      => $vehicle->id,
                        'employee_id'     => $vehicle->employee_id,
                        'lat'             => $data['lat'],
                        'lon'             => $data['lon'],
                        'speed'           => $data['speed'],
                        'heading'         => $data['heading'],
                        'miliage'         => $data['miliage'],
                        'gps_signal'      => $data['gps_signal'],
                        'phone_signal'    => $data['phone_signal'],
                        'ignition_status' => $data['ignition_status'],
                        'date_time'       => $data['date_time'],
                    ]
                );
             } catch (\Exception $e) {
                \Log::info($e->getMessage());
                \Log::info("Some error ocurred processing gps data");
            }
        }

        return [
            'status'  => 'ok', 
            'data'    => '',
            'message' => null
        ];

    }

    public function teltonika(Request $request){

        Validator::make($request->all(), [
            'token' => ['required', function($attribute, $value, $fail) {
                if ($value != env('GPS_TOKEN')) {
                    return $fail("Token no valido");
                }
            }],
            'data'  => 'required|Array',
            'imei'  => 'required|integer'
        ])->validate();

        $gpsDevice = GpsDevice::findByImeiOrCreate($request->imei, 'TELTONIKA', null);

        if(is_null($gpsDevice->vehicle_id)){
            return response()->json([
                'error' => 'Gps sin vehiculo asociado',
            ],409);
        }

        $vehicle = Vehicle::find($gpsDevice->vehicle_id);

        GpsEvent::dispatch($vehicle->id, $gpsDevice->id, $request->data)->onQueue('gps_events');

        foreach ($request->data as $data) {
            
            $events = collect($data["events"]);
            $ignition = $events->where('id',1)->pluck('value')->first();

            CurrentGpsStatus::UpdateOrCreate(
                ['gps_device_id' => $gpsDevice->id],
                [
                    'vehicle_id'      => $gpsDevice->vehicle_id,
                    'employee_id'     => $vehicle->employee_id,
                    'lat'             => $data['latitude'],
                    'lon'             => $data['longitude'],
                    'speed'           => $data['speed'],
                    'heading'         => $data['angle'],
                    'miliage'         => 0,
                    'gps_signal'      => $data['satellites'],
                    'phone_signal'    => 0,
                    'ignition_status' => $ignition ?? 0,
                    'date_time'       => $data['datetime'],
                ]
            );
        }

        return [
            'status'  => 'ok', 
            'data'    => '',
            'message' => "Datos procesados exitosamente"
        ];

    }
}
