<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use App\Models\Route;
use DataTables;
use App\Http\Traits\DocumentTrait;
use App\Exports\DocumentExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\EmployeeType;
use App\Models\Office;
use App\Models\Status;

class ReportsController extends Controller
{
    use DocumentTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $date_start = $request->input('date_start',date('Y-m-d 00:00:00'));
        $date_end = $request->input('date_end',date('Y-m-d H:i:s'));
        return view('reports.index',['date_start' => $date_start, 'date_end' => $date_end]);

        if(!$request->ajax()){
           return view('reports.index', [
           	'date_start' => $date_start,
   			'date_end'	=> $date_end,
           ]);
       }

    }

    public function document(Request $request)
    {
        $date_start = $request->input('date_start');
        $date_end	= $request->input('date_end');
        $offices = \Auth::user()->getOffices();

         $documents = Route::query()
        ->join('documents','routes.id', '=', 'documents.route_id')
        ->join('statuses','statuses.id', '=','documents.status_id')
        ->join('customer_branches','customer_branches.id','=','documents.customer_branch_id')
        ->join('vehicles', 'vehicles.id','=', 'routes.vehicle_id')
        ->join('communes','communes.id','=','customer_branches.commune_id')
        ->join('employees','employees.id','=','documents.seller_id')
        ->join ('employees AS employees_driver','employees_driver.id', '=', 'routes.driver_id')
        ->whereRaw("routes.departure_date BETWEEN '{$date_start}' AND '{$date_end}'")
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
        ]);


      return DataTables::of($documents)
            ->addColumn('action', function ($data) {
                return view('helpers.document.actionButtonReports',['data'=>$data, 'baseRoute'=>'reports'])->render();
            })
            ->editColumn('status', function ($data) {
                return "<h5><span class='badge badge-{$data->color}'>
                            {$data->status}
                        </span></h5>";
            })
            ->editColumn('customer', function ($data) {
    			return "<h6>
				  		    {$data->customer} <br>
				  		    <small class='text-muted'>{$data->address}, {$data->commune}</small>
				        </h6>";
            })

            ->filterColumn('name_seller', function($query, $keyword) {
                $sql = "lower(CONCAT(employees.name,' ',employees.lastname))  like lower(?)"; 
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('name_driver', function($query, $keyword) {
                $sql = "lower(CONCAT(employees_driver.name,' ',employees_driver.lastname))  like lower(?)"; 
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            
            ->rawColumns(['customer', 'status', 'action', 'status_code', 'vehicle_id'])
            ->removeColumn('address')
            ->removeColumn('commune')
            ->removeColumn('color')
            ->make(true);

    }

        public function export($date_start,$date_end)
        {
            return Excel::download(new DocumentExport($date_start,$date_end),'Documentos.xlsx');
            

        }



   
}
