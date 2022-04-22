<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Office;
use App\Models\Document;
use App\Models\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use DataTables;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Foreach_;
use SebastianBergmann\Environment\Console;

use DispatchesJobs, ValidatesRequests;

class ProcessingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $offices =  Office::select('id', 'label')->get();
        return view('processing.index', ['offices' => $offices]);
    }

    public function patente(Request $request)
    {
        $office_id = $request->input('office_id');

        // Extraigo la lista de patentes que poseen rutas en estatus: Reparto
        $data = Vehicle::query()
            ->join('routes', 'routes.vehicle_id', '=', 'vehicles.id')
            ->join('documents', 'documents.route_id', '=', 'routes.id')
            ->where('routes.status_id', 3)
            ->where('documents.status_id', 4)
            ->where('vehicles.office_id', $office_id)
            ->select([
                'vehicles.code AS code',
                'vehicles.id AS id'
            ])
            ->groupBy('vehicles.code', 'vehicles.id')
            ->orderByRaw('vehicles.code::INTEGER ASC')->get();
        return $data;
    }

    public function post_select(Request $request)
    {

        // Extraigo la lista de los documentos que estan en estatus: Reparto
        $vehicle_id = $request->input('vehicle_id');
        $office_id = $request->input('office_id');

        if ($office_id == 0) {

            $data = Route::query()
                ->join('documents', 'routes.id', '=', 'documents.route_id')
                ->join('statuses', 'statuses.id', '=', 'documents.status_id')
                ->join('vehicles', 'vehicles.id', 'routes.vehicle_id')
                ->join('offices', 'offices.id', '=', 'vehicles.office_id')
                ->where('routes.status_id', 3)
                ->where('documents.status_id', 4)
                ->select([
                    'vehicles.code AS vehicle',
                    'documents.id AS id',
                    'routes.code AS ruta',
                    'documents.code AS code',
                    'documents.order_number AS order_number',
                    'offices.label AS office',
                    'statuses.color AS color',
                    'statuses.label AS label'
                ])
                ->groupBy('vehicles.code', 'documents.id', 'routes.code', 'documents.code', 'documents.order_number', 'offices.label', 'statuses.color', 'statuses.label')
                ->orderByRaw('vehicles.code::INTEGER');


            if (request()->ajax()) {
                return DataTables::of($data)
                    ->addColumn('action', function ($data) {

                        $check = '<label class="customcheck"><input type="checkbox" name="doc_check[]" id="' . $data->id . '" value="' . $data->id . '" class=""><span class="checkmark"></span></label>';

                        return $check;
                    })
                    ->editColumn('label', function ($data) {
                        return "<h5><span class='badge badge-{$data->color}'>
                                 {$data->label}
                             </span></h5>";
                    })
                    ->rawColumns(['action', 'label'])
                    ->removeColumn('color')
                    ->make(true);
            }
        } else {
            $data->type == 'gps' ? 'GPS' : 'TELEFONO';
            // Extraigo la lista de los documentos que estan en estatus: Reparto
            $vehicleSql = $vehicle_id == 0 ? 'routes.vehicle_id' : $vehicle_id;
            $data = Route::query()
                ->join('documents', 'routes.id', '=', 'documents.route_id')
                ->join('statuses', 'statuses.id', '=', 'documents.status_id')
                ->join('vehicles', 'vehicles.id', 'routes.vehicle_id')
                ->join('offices', 'offices.id', '=', 'vehicles.office_id')
                ->whereRaw("routes.vehicle_id = {$vehicleSql}")
                ->where('vehicles.office_id', '=', $office_id)
                ->where('routes.status_id', 3)
                ->where('documents.status_id', 4)
                ->select([
                    'vehicles.code AS vehicle',
                    'documents.id AS id',
                    'routes.code AS ruta',
                    'documents.code AS code',
                    'documents.order_number AS order_number',
                    'offices.label AS office',
                    'statuses.color AS color',
                    'statuses.label AS label'
                ])
                ->groupBy('vehicles.code', 'documents.id', 'routes.code', 'documents.code', 'documents.order_number', 'offices.label', 'statuses.color', 'statuses.label')
                ->orderByRaw('vehicles.code::INTEGER ASC');

            if (request()->ajax()) {
                return DataTables::of($data)
                    ->addColumn('action', function ($data) {

                        $check = '<label class="customcheck"><input type="checkbox" name="doc_check[]" id="' . $data->id . '" value="' . $data->id . '" class=""><span class="checkmark"></span></label>';
                        return $check;
                    })
                    ->editColumn('label', function ($data) {
                        return "<h5><span class='badge badge-{$data->color}'>
                                        {$data->label}
                                    </span></h5>";
                    })
                    ->rawColumns(['action', 'label'])
                    ->removeColumn('color')
                    ->make(true);
            }
        }
    }

    public function checkdocument(Request $request)
    {
        // Cambio el estatus del documento, dependiendo de la elección
        $this->validate($request, [
            'doc_check' => 'required'
        ]);

        $array_check = $request->input('doc_check');    //Array de checkbox
        $status = $request->status_button;              //Estatus boton selección
        Log::info(json_encode($array_check));
        foreach ($array_check as $lista_check) {

            Document::where('id', $lista_check)
                ->update([
                    'status_id' => $status
                ]);

            $flat_route = DB::selectOne("
                    WITH data AS (
                        SELECT
                            routes.id
                        FROM routes
                        JOIN documents ON documents.route_id = routes.id
                        WHERE documents.id = '{$lista_check}'
                    )
                    SELECT 
                        documents.order_number,
                        documents.status_id,
                        routes.id
                    FROM documents
                    JOIN routes ON routes.id = documents.route_id
                    JOIN data ON data.id = routes.id
                    GROUP BY documents.order_number,routes.id,documents.status_id
                    ORDER BY documents.status_id ASC
                ");
            Log::info(json_encode($flat_route->status_id));

            if ($flat_route->status_id > 5) {
                \Log::info("Cambia Estatus");
                Route::query()
                    ->where('id', $flat_route->id)
                    ->update([
                        'status_id' => 26,
                        'updated_at' => DB::raw('now()')
                    ]);
            }
        }
    }
}
