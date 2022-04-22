<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Libs\Rest;

class GetGeotabPosition extends Command
{
    private $credentials; 
    private $url;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:geotab';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtiene las posiciones de los dispositivos geotab';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->credentials = [
            "database" => "ch_prisa",
            "userName" => "cdiazr@prisa.cl",
            "password" => "prisa2018"
        ];

        $this->url = "https://movistar149.geotab.com/apiv1/Get";

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $devices       = $this->getData('Device');
        $devicesStatuses = $this->getData('DeviceStatusInfo');
        
        if(count($devices['result']) <= 0){
            return;
        }

        $devicesChunk = array_chunk($devices['result'], 40);

        $ids = array_column(
                array_column(
                    $devicesStatuses['result'],
                    'device'
                ), 
                'id'
            );

        foreach ($devicesChunk as $devices) {
            $data = [];
            foreach ($devices as $device) {
                $device  = (object) $device;
                $id    = $device->id;
                $name  = $device->name;
                $plate = $device->licensePlate;
                
                $key = array_search($device->id, $ids);

                if(!$key){
                    continue;
                }
                $deviceStatus = (object)$devicesStatuses['result'][$key];

                $data[] = [
                    'type'            => "GEOTAB",
                    'code'            => $device->id,
                    'plate'           => $device->licensePlate." (".$device->id.")",
                    'imei'            => $device->id,
                    'label'           => filter_var($device->name, FILTER_SANITIZE_NUMBER_INT),
                    'lat'             => $deviceStatus->latitude,
                    'lon'             => $deviceStatus->longitude,
                    'speed'           => $deviceStatus->speed,
                    'heading'         => $deviceStatus->bearing,
                    'miliage'         => 0,
                    'gps_signal'      => 0,
                    'phone_signal'    => 0,
                    'ignition_status' => (int) $deviceStatus->isDriving,
                    'date_time'       => date('Y-m-d H:i:s', strtotime($deviceStatus->dateTime))
                ];
            }
            $this->sendData($data);
        }
    }

    //envio localmente el request
    private function sendData($data){
        $request = new Request(['data' => $data]);
        $dataCount = count($data);
        //\Log::info("Enviando {$dataCount} datos de posicion");
        app('App\Http\Controllers\Api\GpsStatusController')->store($request);
    }

    //obtengo los datos por medio de la api del geotab
    private function getData($type){
        $querystring = ["typeName" => $type, "credentials" => json_encode($this->credentials)];
        return Rest::callApi('GET',$this->url, $querystring);
    }

}
