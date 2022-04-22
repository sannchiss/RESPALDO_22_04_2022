<?php

namespace App\Http\Controllers\Maintainer\Adjustment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Vehicle;
use App\Models\Status;
use DataTables;
use Validator;
use DB;


class RouteController extends Controller
{
    protected $view = 'maintainer.adjustment.route';

    public function index(Request $request){
	    $office_id  = $request->input('office_id',0);
	    $offices = \Auth::user()->getOffices();
	    $searching   = $request->input('searching', null);

    	if(!$request->ajax()){
            return view("{$this->view}.index", ['offices' => $offices, 'office_id' => $office_id]);
        }

        $route = Route::query()
        	->when($office_id != 0, function ($query) use ($office_id) {
        		return $query->where('routes.office_id', '=', $office_id);
        	}, function ($query) use ($offices) {
        		return $query->whereIn('routes.office_id', $offices->pluck('id'));
        	})
        	->join('vehicles','vehicles.id', '=', 'routes.vehicle_id')
        	->join('statuses','statuses.id', '=','routes.status_id')
        	->join('offices', 'offices.id', '=', 'routes.office_id')
        	->where('routes.code','=', $searching)
        	->select([
        		'routes.id AS id',
        		'vehicles.label AS vehicle',
        		'routes.code AS route',
        		'statuses.label AS status',
        		'offices.label AS office',
        		'routes.created_at',
        		'routes.updated_at'
        	]);

        return DataTables::of($route)
        	 ->addColumn('action', function ($route) {
                return view('helpers.actionButtons',[
                    'show' => ['reset','edit'],
                    'updateRoute' => "{$this->view}.edit",
                    'resetRoute' => "{$this->view}.destroy",
                    'id' => $route->id
                ]);
            })->make(true);

    }

    public function edit($id)
    {
        $data = Route::join('vehicles','vehicles.id', '=', 'routes.vehicle_id')
        			->where('routes.id', '=', $id)
        			->select(['vehicles.code AS vehicle_code', 'routes.id', 'routes.code as route'])
        			->first();

        return view("{$this->view}.update",['data' => $data]);
    }

    public function update(Request $request, $id)
    {
    	Validator::make($request->all(), 
    		[ 'vehicle_code'  => 'required|numeric|exists:vehicles,code']
    	)->validate();

    	$vehicle_code = $request->input('vehicle_code');
    	$vehicle = Vehicle::findByCodeAndUpdateOffice($vehicle_code);
        $route = Route::find($id);
        $route->vehicle_id = $vehicle->id;
        $route->save();
    }

    public function destroy($id)
    {
        $routeStatus = Status::findByCode('ROUTE','PENDING_DEPARTURE')->id;
        $documentStatus = Status::findByCode('DOCUMENT','PENDING_DEPARTURE')->id;
        $documentDetailStatus = Status::findByCode('DOCUMENT_DETAIL','PENDING_DEPARTURE')->id;
        DB::select("
            WITH _routes AS (
                    SELECT routes.id 
                    FROM routes
                    WHERE routes.id = {$id}
                ),
                _update_routes AS (
                    UPDATE routes 
                    SET status_id = {$routeStatus}, 
                        departure_date = now()  
                    WHERE id IN (SELECT id FROM _routes)
                    RETURNING routes.id
                ),
                _update_documents AS (
                    UPDATE documents 
                        SET status_id = {$documentStatus} 
                    WHERE route_id IN (SELECT id FROM _update_routes)
                    RETURNING documents.id 
                )
                UPDATE document_details SET status_id = {$documentDetailStatus} WHERE document_id IN (SELECT id FROM _update_documents);
        ");

    }


}
