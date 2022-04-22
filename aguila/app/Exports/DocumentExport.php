<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Route;
use DB;

class DocumentExport implements FromView
{
	private $dateStart;
	private $dateEnd;

   public function __construct(string $dateStart, string $dateEnd)
   {
       $this->dateStart = $dateStart;
       $this->dateEnd 	= $dateEnd;   
   }

	public function view(): View
   {
   	$dateStart = $this->dateStart;
    $dateEnd   = $this->dateEnd;
    $offices = \Auth::user()->getOffices();
    \Log::info("id_excel".$offices->pluck('id'));
       $datas = Route::query() 
        ->join('documents','routes.id', '=', 'documents.route_id')
        ->join('statuses','statuses.id', '=','documents.status_id')
        ->join('customer_branches','customer_branches.id','=','documents.customer_branch_id')
        ->join('vehicles', 'vehicles.id','=', 'routes.vehicle_id')
        ->join('communes','communes.id','=','customer_branches.commune_id')
        ->join('employees','employees.id','=','documents.seller_id')
        ->join ('employees AS employees_driver','employees_driver.id', '=', 'routes.driver_id')
        ->whereRaw("routes.departure_date BETWEEN '{$dateStart}' AND '{$dateEnd}'")
        ->whereIn('routes.office_id',$offices->pluck('id'))
        ->select([
                'documents.id AS id',                                                                           // ID Detalle del Documento
                'routes.code AS route',                                                                         // Ruta del Documento
                'documents.order_number AS order',                                                              // Número de Orden 
                'documents.code AS document',                                                                   // Número de Documento
                'statuses.label AS status',                                                                     // Estatus del Documento
                'customer_branches.label AS customer',                                                          // Nombre del Cliente
                'customer_branches.rut AS rut',                                                                 // Rut del Cliente 
                DB::raw("CONCAT(employees.name,' ',employees.lastname)  AS name_seller"),                       // Nombre Completo Vendedor
                'employees.rut AS rut_seller',                                                                  // Rut del Vendedor
                DB::raw("CONCAT(employees_driver.name,' ',employees_driver.lastname)  AS name_driver"),         // Nombre Completo Conductor 
                'employees_driver.rut AS rut_driver',                                                           // Rut del Vendedor
                'vehicles.code AS patent',                                                                      // Patente del Vehiculo
                'documents.packages AS packages',                                                               // Cantidad de Bultos
    			'documents.created_at AS created_at',                                                           // Fecha de Creación del Documento
                'documents.processed_date AS processed_date',                                                   // Fecha de Gestión del Documento                
                'routes.vehicle_id AS vehicle_id',                                                              // ID Ruta del Vehiculo
                'statuses.color AS color',                                                                      // Estatus Documento Color 
                'customer_branches.address',           
    			'communes.label AS commune'
       ])
           ->get();

   	return view('reports.export', [
           'dateStart' => $dateStart,
       	   'dateEnd'   => $dateEnd,
       	   'datas'	   => $datas
       ]);
   }
}