<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Models\Vehicle;
use App\Models\GpsDevice;
use App\Mail\PanicEvent;
use DB;
use phpDocumentor\Reflection\Types\Integer;

class GpsEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $vehicleId, $deviceId, $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Int $vehicleId, Int $deviceId, Array $data)
    {
        $this->vehicleId = $vehicleId;
        $this->deviceId  = $deviceId;
        $this->data      = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->data as $value) {
            if($this->checkPanicButton($value)){
                break;
            }
        }
    }

    protected function checkPanicButton($data) : bool {
        $events = collect($data["events"]);
        $isPanic = (boolean) $events->where('id',2)->pluck('value')->first();

        return $isPanic 
            ? $this->sendEmail($data)
            : false;
    }

    protected function sendEmail($data) : bool {

        $emails = explode(",", env("PANIC_EMAILS"));

        Mail::to($emails)
        ->send(new PanicEvent($this->vehicleId, $this->deviceId, $data));

        return true;
    }
}
