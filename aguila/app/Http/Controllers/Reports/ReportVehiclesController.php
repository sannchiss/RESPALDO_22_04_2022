<?php

namespace App\Http\Controllers\Reports;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;
use App\Models\Route;
use DataTables;
use App\Exports\DocumentExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\EmployeeType;
use App\Models\Office;
use App\Models\Status;
use App\Models\Vehicle;


class ReportVehiclesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $date_start = $request->input('date_start',date('Y-m-d 00:00:00'));
        $date_end = $request->input('date_end',date('Y-m-d H:i:s'));
        return view('reports.vehicles.index',['date_start' => $date_start, 'date_end' => $date_end]);

        if(!$request->ajax()){
           return view('reports.vehicles.index', [
           	'date_start' => $date_start,
   			'date_end'	=> $date_end,
           ]);
       }
    }

    public function document(Request $request)
    {
        $date_start = $request->input('date_start');
        $date_end	= $request->input('date_end');

        $documents = Vehicle::query()
        ->join('routes','routes.vehicle_id','=','vehicles.id')
        ->join('offices', 'vehicles.office_id', '=', 'offices.id')
        ->whereRaw("routes.created_at BETWEEN '{$date_start}' AND '{$date_end}'")
        ->select([
            'vehicles.id AS id',
            'offices.label AS office',
            'vehicles.code AS code',
            'vehicles.label AS label',
            'vehicles.vehicle_condition_id AS vehicle_condition_id',
            
        ])
        
        ->groupBy('vehicles.id','offices.label','vehicles.code', 'vehicles.label')
        ->orderByRaw('vehicles.id ASC');  

        if(request()->ajax()){
            return DataTables::of($documents)
            ->addColumn('action', function ($data) use ($date_start , $date_end ) {
                return view('helpers.document.actionButtonVehicleReports',['data'=>$data,'date_start'=>$date_start, 'date_end'=> $date_end, 'baseRoute'=>'reports'])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
        }

    }

    public function detailRouteVehicle($vehicle_id, $date_start, $date_end)
    {  
        $data = Vehicle:: query()
        ->join('routes','routes.vehicle_id','=','vehicles.id')        
        ->join('statuses','statuses.id', '=','routes.status_id')
        ->where('vehicles.id','=',$vehicle_id)
        ->whereRaw("routes.created_at BETWEEN '{$date_start}' AND '{$date_end}'")
        ->select([
            'routes.id AS id',
            'routes.code AS code',
            'routes.status_id AS status_id',
            'statuses.label AS status',          // Estatus de Ruta
            'statuses.color AS color',           // Estatus Ruta Color 
            'routes.created_at AS created_at'    // Fecha de Creación

        ])
        ->groupBy('routes.id','routes.code', 'routes.status_id','statuses.label','statuses.color')
        ->orderByRaw('routes.id ASC');  

        if(request()->ajax()){
            return DataTables::of($data)
            ->editColumn('status', function ($data) {
                return "<h5><span class='badge badge-{$data->color}'>
                            {$data->status}
                        </span></h5>";
            })

            ->rawColumns(['action','status'])
            ->removeColumn('color')

            ->make(true);

            
        }

    }



}
