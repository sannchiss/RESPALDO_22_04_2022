<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
class PanicEvent extends Mailable
{
    use Queueable, SerializesModels;
    protected $vehicleId, $deviceId, $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Int $vehicleId, Int $deviceId, Array $data)
    {
        $this->vehicleId = $vehicleId;
        $this->deviceId  = $deviceId;
        $this->data      = (object) $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   

        $routes = DB::select("
            SELECT 
                code
            FROM routes
            WHERE vehicle_id = {$this->vehicleId} 
            AND departure_date::date = '{$this->data->datetime}'::date
        ");

        $vehicle = DB::selectOne("
            SELECT 
                vehicles.code,
                vehicles.plate_number,
                CONCAT(employees.name,' ', employees.lastname) as fullname
            FROM vehicles
            LEFT JOIN employees ON employees.id = vehicles.employee_id
            WHERE vehicles.id = {$this->vehicleId}
        ");

        $device = DB::selectOne("
            SELECT 
                phone_number
            FROM gps_devices
            WHERE id = {$this->deviceId}
        ");

        $data = $this->data;
        $events = collect($data->events);
        $ignition = $events->where('id',1)->pluck('value')->first();

        return $this->from('noreply@prisa.cl', 'Sistema Aguila')
        ->subject('Alerta panico en vehiculo')
        ->view('mail.panic_event', compact("routes", "vehicle","device", "data", "ignition") );
    }
}
