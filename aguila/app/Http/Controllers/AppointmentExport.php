<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Appointment;
use App\Models\AppointmentDetail;
use DB;

class AppointmentExport extends Controller
{
    private $dateStart;
	private $dateEnd;

 /*  public function __construct(string $dateStart, string $dateEnd)
   {
       $this->dateStart = $dateStart;
       $this->dateEnd 	= $dateEnd;   
   }

	public function view(): View
   {
   	$dateStart = $this->dateStart;
   	$dateEnd   = $this->dateEnd;

       $datas = Route::query()
       ->join('documents','routes.id', '=', 'documents.route_id')
       ->join('statuses','statuses.id', '=','documents.status_id')
       ->join('customer_branches','customer_branches.id','=','documents.customer_branch_id')
       ->join('vehicles', 'vehicles.id','=', 'routes.vehicle_id')
       ->join('communes','communes.id','=','customer_branches.commune_id')
       ->whereRaw("routes.departure_date BETWEEN '{$dateStart}' AND '{$dateEnd}'")

       ->select([
               'documents.id as id',
               'routes.code AS route',
               'routes.vehicle_id AS vehicle_id',
               'documents.order_number AS order',
               'documents.code AS document',
               'statuses.label AS status',
               'statuses.color AS color',
               'documents.processed_date',
               'customer_branches.label AS customer',
               'customer_branches.address',
               'vehicles.code as patent',
               'customer_branches.lat AS lat',
               'customer_branches.lon AS lon',
               'communes.label AS commune',
               'documents.received_by',
               'documents.observation'
       ]);
       }*/
}
