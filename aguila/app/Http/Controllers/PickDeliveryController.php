<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Route;
use DataTables;
use App\Models\Office;
use Illuminate\Support\Facades\Log;
use App\Models\VehicleCondition;
use App\Models\Status;
use App\Http\Traits\DocumentTrait;

class PickDeliveryController extends Controller
{
    use DocumentTrait;

    public function index(Request $request)
    {

        if (!$request->ajax()) {
            return view('pick_delivery.index', $this->getDatos($request));
        }
        return response()->json($this->getDatos($request));
    }
    // Metodo permite escoger por rango de fecha las rutas con documentos que sean distintos del estatus ruta:Reparto
    // Sino escoger todos los documentos donde el estatus ruta: Reparto
    public function methodDate($route_status_id, $date_start, $date_end)
    {

        if ($route_status_id != 3) {
            $labelSql = " routes.departure_date BETWEEN '{$date_start}' AND '{$date_end}' ";
        } else {
            $labelSql = " routes.status_id = 3 ";
        }
        return $labelSql;
    }

    private function getDatos(Request $request)
    {

        $office_id  = $request->input('office_id', 0);
        $vehicle_condition_id  = $request->input('vehicle_condition_id', 0);
        $date_start = $request->input('date_start', date('Y-m-d 00:00:00'));
        $date_end   = $request->input('date_end', date('Y-m-d 23:59:59'));
        $role_id     = \Auth::user()->role_id;
        $route_status_id =  $request->input('route_status_id', 0);

        $offices = Office::join(
            'role_offices',
            function ($join) use ($role_id) {
                $join->on('role_offices.office_id', '=', 'offices.id')
                    ->on('role_offices.role_id', '=', DB::raw($role_id));
            }
        )
            ->select([
                'offices.id',
                'offices.label'
            ])->get();

        $vehicle_conditions = VehicleCondition::get();
        $statusRoutes = Status::where('system_area_id', 2)->get();
        $labelSql = $this->methodDate($route_status_id, $date_start, $date_end);
        $routes = DB::Select("
    	WITH _system_areas AS( 
    		SELECT id, code FROM system_areas WHERE system_areas.code IN ('ROUTE','DOCUMENT')
    	),
        _offices AS(
            SELECT office_id 
            FROM role_offices 
            WHERE role_id = {$role_id}
        ),
    	DATA AS(
	    SELECT 
            routes.id AS route_id,
            COUNT(routes.id) AS qty_routes,
	    	vehicles.id AS vehicle_id,
	    	vehicles.label as vehicle_label,
	    	vehicles.carrier_id as vehicle_carrier,
            vehicle_conditions.code as condition
        FROM routes 
            JOIN vehicles ON routes.vehicle_id = vehicles.id
            JOIN vehicle_conditions ON vehicle_conditions.id = vehicles.vehicle_condition_id
	    	JOIN statuses ON statuses.id = routes.status_id
	    		AND statuses.system_area_id IN (SELECT id FROM _system_areas WHERE _system_areas.code = 'ROUTE')
	    		AND statuses.code != 'PENDING_DEPARTURE'
        WHERE 
            {$labelSql}
            AND routes.office_id IN (SELECT _offices.office_id FROM _offices)
            AND (routes.office_id  = {$office_id} OR {$office_id} = 0)
            AND (vehicles.vehicle_condition_id = {$vehicle_condition_id} OR {$vehicle_condition_id} = 0)
            AND (routes.status_id = {$route_status_id} OR {$route_status_id} = 0)
            GROUP BY 1,3,4,5,6
    	)
    	SELECT 
        	DATA.qty_routes,
            DATA.vehicle_id,
            DATA.vehicle_label,
            DATA.vehicle_carrier,
            min(documents.processed_date) as first_time,
            max(documents.processed_date) as last_time,
            count(DISTINCT documents.order_number) AS orders,
            count(documents.id) as qty_document,
            count(DISTINCT documents.route_id) AS count_routes,
            count(DISTINCT documents.customer_branch_id) as qty_customer,
            SUM(CASE WHEN DATA.condition = 'OWN' THEN 1 ELSE 0 END) AS own,
            SUM(CASE WHEN DATA.condition = 'RENTED' THEN 1 ELSE 0 END) AS rented,
            SUM(CASE WHEN statuses.code IN ('IN_DELIVERY','REDESPACHING','REJECTED','ACCEPTED','PARTIAL_REJECTION') 
                THEN 1 ELSE 0 END) AS processed,
        	SUM(CASE WHEN statuses.code = 'IN_DELIVERY' THEN 1 ELSE 0 END) AS in_delivery,
        	SUM(CASE WHEN statuses.code = 'REDESPACHING' THEN 1 ELSE 0 END) AS redespaching,
        	SUM(CASE WHEN statuses.code = 'REJECTED' THEN 1 ELSE 0 END) AS rejected,
        	SUM(CASE WHEN statuses.code = 'ACCEPTED' THEN 1 ELSE 0 END) AS accepted,
        	SUM(CASE WHEN statuses.code = 'PARTIAL_REJECTION' THEN 1 ELSE 0 END) AS partial_rejection
    	FROM documents 
    		JOIN DATA ON DATA.route_id = documents.route_id
    		JOIN statuses ON statuses.id = documents.status_id
	    		AND statuses.system_area_id IN (SELECT id FROM _system_areas WHERE _system_areas.code = 'DOCUMENT')
        GROUP BY 1,2,3,4
        ORDER BY DATA.vehicle_label::integer ASC
        ");

        $routeCollect = collect($routes);
        $totalInDelivery       = $routeCollect->sum('in_delivery');
        $totalRedespaching       = $routeCollect->sum('redespaching');
        $totalRejected           = $routeCollect->sum('rejected');
        $totalPartialRejection = $routeCollect->sum('partial_rejection');
        $totalAccepted            = $routeCollect->sum('accepted');
        $totalVehicles           = $routeCollect->count('vehicle_id');
        $totalDocuments           = $routeCollect->sum('qty_document');
        $totalRoutes           = $routeCollect->sum('qty_routes');
        $totalOwn              = $routeCollect->sum('own');
        $totalRented           = $routeCollect->sum('rented');
        $totalOrders           = $routeCollect->sum('orders');
        $totalCustomers        = $routeCollect->sum('qty_customer');

        return [
            'vehicle_conditions'    => $vehicle_conditions,
            'offices'               => $offices,
            'totalRoutes'            => $totalRoutes,
            'statusRoutes'          => $statusRoutes,
            'totalDocuments'        => $totalDocuments,
            'date_start'            => $date_start,
            'date_end'                => $date_end,
            'routes'                 => $routes,
            'totalInDelivery'         => $totalInDelivery,
            'totalRedespaching'     => $totalRedespaching,
            'totalRejected'         => $totalRejected,
            'totalPartialRejection' => $totalPartialRejection,
            'totalAccepted'            => $totalAccepted,
            'totalVehicles'            => $totalVehicles,
            'totalIncidence'        => $totalRedespaching + $totalRejected + $totalPartialRejection,
            'totalRented'           => $totalRented,
            'totalOwn'              => $totalOwn,
            'office_id'             => $office_id,
            'vehicle_condition_id'  => $vehicle_condition_id,
            'route_status_id'       => $route_status_id,
            'totalOrders'           => $totalOrders,
            'totalCustomers'        => $totalCustomers,
        ];
    }

    public function getDocuments(Request $request)
    {
        $vehicle_id = $request->input('vehicle_id');
        $date_start = $request->input('date_start');
        $date_end    = $request->input('date_end');
        $route_status_id = $request->input('route_status_id');
        $labelSql = $this->methodDate($route_status_id, $date_start, $date_end);

        $documents = Route::query()
            ->join('documents', 'routes.id', '=', 'documents.route_id')
            ->join('statuses', 'statuses.id', '=', 'documents.status_id')
            ->join('customer_branches', 'customer_branches.id', '=', 'documents.customer_branch_id')
            ->join('communes', 'communes.id', '=', 'customer_branches.commune_id')
            ->where('routes.vehicle_id', '=', $vehicle_id)
            ->whereRaw("{$labelSql}")
            ->select([
                'documents.id as id',
                'routes.code AS route',
                'routes.vehicle_id AS vehicle_id',
                'documents.order_number AS order',
                'documents.code AS document',
                'statuses.label AS status',
                'statuses.color AS color',
                'statuses.code AS status_code',
                'documents.processed_date',
                'customer_branches.label AS customer',
                'customer_branches.address',
                'customer_branches.lat AS lat',
                'customer_branches.lon AS lon',
                'communes.label AS commune',
                'documents.received_by',
                'documents.observation'
            ]);

        //Log::info($documents->toSql());

        return DataTables::of($documents)
            ->addColumn('action', function ($data) {
                return view('helpers.document.actionButton', ['data' => $data, 'baseRoute' => 'pick_delivery'])->render();
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

            ->rawColumns(['customer', 'status', 'action', 'status_code', 'lat', 'lon', 'vehicle_id'])
            ->removeColumn('address')
            ->removeColumn('commune')
            ->removeColumn('color')
            ->make(true);
    }

    public function getVehicle(Request $request)
    {
        $vehicle_id = $request->input('vehicle_id');
        $date_start = $request->input('date_start');
        $date_end   = $request->input('date_end');
        $route_status_id = $request->input('route_status_id');
        $labelSql = $this->methodDate($route_status_id, $date_start, $date_end);

        $data = DB::select("
            SELECT
                employees.name ||' '|| employees.lastname AS driver,
                vehicles.label AS vehicle,
                cellphones.phone_number AS phone_number, 
                COUNT(DISTINCT documents.route_id) AS qty_routes,
                COUNT(documents.id) as qty_documents,
                count(DISTINCT documents.order_number) AS qty_orders,
                SUM(CASE WHEN statuses.code = 'IN_DELIVERY' THEN 1 ELSE 0 END) AS in_delivery,
                SUM(CASE WHEN statuses.code = 'REDESPACHING' THEN 1 ELSE 0 END) AS redespaching,
                SUM(CASE WHEN statuses.code = 'REJECTED' THEN 1 ELSE 0 END) AS rejected,
                SUM(CASE WHEN statuses.code = 'ACCEPTED' THEN 1 ELSE 0 END) AS accepted,
                SUM(CASE WHEN statuses.code = 'PARTIAL_REJECTION' THEN 1 ELSE 0 END) AS partial_rejection
            FROM vehicles
                LEFT JOIN routes ON vehicles.id = routes.vehicle_id 
                LEFT JOIN documents ON routes.id = documents.route_id
                LEFT JOIN statuses ON statuses.id = documents.status_id
                LEFT JOIN employees ON employees.id = vehicles.employee_id
                LEFT JOIN cellphones ON cellphones.employee_id = employees.id
                WHERE vehicles.id = {$vehicle_id} AND {$labelSql}
                GROUP BY 1,2,3
        ");

        //Log::info(json_encode($routes));


        return response()->json([
            'general' =>  view('pick_delivery.vehicle_data', ['data' => $data[0]])->render(),
            'vehicle' =>  $data[0]->vehicle
        ]);
    }

    public function getCustomerPositions(Request $request)
    {
        $vehicle_id = $request->input('vehicle_id');
        $date_start = $request->input('date_start');
        $date_end   = $request->input('date_end');
        $route_status_id = $request->input('route_status_id');
        $labelSql = $this->methodDate($route_status_id, $date_start, $date_end);

        $customers = DB::select("
            SELECT
                string_agg(distinct documents.order_number::varchar, ', ') AS order_numbers,
                string_agg(distinct documents.code::varchar, ', ') AS documents,
                string_agg(distinct routes.code::varchar, ', ') AS routes,
                customer_branches.label AS customer,
                customer_branches.address || ', ' || communes.label AS address,
                customer_branches.lat AS lat,
                customer_branches.lon AS lon
            FROM routes 
                JOIN documents ON routes.id = documents.route_id
                JOIN customer_branches ON customer_branches.id = documents.customer_branch_id
                JOIN communes ON communes.id = customer_branches.commune_id
            WHERE routes.vehicle_id  = {$vehicle_id} 
                AND {$labelSql}
            GROUP BY 4, 5, 6, 7;
            ");
        Log::info("SELECT
        string_agg(distinct documents.order_number::varchar, ', ') AS order_numbers,
        string_agg(distinct documents.code::varchar, ', ') AS documents,
        string_agg(distinct routes.code::varchar, ', ') AS routes,
        customer_branches.label AS customer,
        customer_branches.address || ', ' || communes.label AS address,
        customer_branches.lat AS lat,
        customer_branches.lon AS lon
    FROM routes 
        JOIN documents ON routes.id = documents.route_id
        JOIN customer_branches ON customer_branches.id = documents.customer_branch_id
        JOIN communes ON communes.id = customer_branches.commune_id
    WHERE routes.vehicle_id  = {$vehicle_id} 
        AND {$labelSql}
    GROUP BY 4, 5, 6, 7");
        return response()->json([
            'customers' => $customers
        ]);
    }
}
