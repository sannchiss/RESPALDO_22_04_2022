<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Route;
use DataTables;
use App\Http\Traits\DocumentTrait;
use App\Models\CustomerBranch;
use DB;
use Validator;

class QuestController extends Controller
{
	use DocumentTrait;

    public function index(Request $request){

    	$date_start = $request->input('date_start',date('Y-m-d 00:00:00'));
        $date_end   = $request->input('date_end',date('Y-m-d 23:59:59'));
    	if(!$request->ajax()){

            return view('quest.index',[
            	'date_start' => $date_start,
    			'date_end'	 => $date_end
    		]);
        }
        $search_type = $request->input('search_type', null);
	    $searching   = $request->input('searching', null);
	    $customer_id = $request->input('customer_id', null);

        if($search_type == 'CUSTOMER'){
        	$toValidate = [
        		'customer_id'  => 'required|integer',
        		'date_start'   => 'required|date_format:"Y-m-d"',
            	'date_end'     => 'required|date_format:"Y-m-d"',
        	];
        } else {
        	$toValidate = [
        		'searching'	   => 'required|numeric',
        	];
        }

        Validator::make($request->all(), 
        	array_merge(['search_type'  => 'required|string'], $toValidate)
    	)->validate();
    	    
	    $documents = Route::query()
	    	->join('documents','routes.id', '=', 'documents.route_id')
	    	->join('statuses','statuses.id', '=','documents.status_id')
	    	->join('customer_branches','customer_branches.id','=','documents.customer_branch_id')
	    	->join('communes','communes.id','=','customer_branches.commune_id')
	    	->join('vehicles','vehicles.id', '=', 'routes.vehicle_id')
	    	->when($search_type == 'CUSTOMER', function ($q) use ($customer_id, $date_start, $date_end) {
	    		return $q->where('customer_branches.id', '=', $customer_id)
	    		  		->whereRaw("routes.departure_date BETWEEN '{$date_start} 00:00:00' AND '{$date_end} 23:59:59'");
			})
			->when($search_type != 'CUSTOMER', function ($q) use ($search_type, $searching) {
				$toSearch = '';
	    		switch ($search_type) {
	    			case 'DOCUMENT':
	    				$toSearch = 'documents.code';
	    				break;
	    			case 'ORDER':
	    				$toSearch = 'documents.order_number';
	    				break;
	    			case 'ROUTE':
	    				$toSearch = 'routes.code';
	    				break;
	    		}
	    		return $q->where($toSearch, '=', $searching);
			})
	    	->select([
	    		'documents.id as id',
	    		'vehicles.label AS vehicle',
	    		'routes.vehicle_id AS vehicle_id',
	    		'routes.code AS route',
	    		'documents.order_number AS order',
	    		'documents.code AS document',
	    		'statuses.label AS status',
	    		'statuses.code AS status_code',
	            'statuses.color AS color',
	    		'documents.processed_date',
	    		'customer_branches.label AS customer',
	    		'customer_branches.address',
	    		'customer_branches.lat AS lat',
                'customer_branches.lon AS lon',
	    		'communes.label AS commune',
	    		'documents.received_by',
				'documents.observation',
				DB::raw("'aguila' AS data_type")
			]);

			if($search_type == 'ORDER' && $documents->count() < 1){
				$documents = $this->getOrderFromWms($searching);
			}
			
	    	return DataTables::of($documents)
	            ->addColumn('action', function ($data) {
	                return view('helpers.document.actionButton',['data'=>$data, 'baseRoute'=>'quest'])->render();
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

	            ->rawColumns(['customer', 'status', 'action','status_code', 'lat', 'lon', 'vehicle_id'])
	            ->removeColumn('address')
	            ->removeColumn('commune')
	            ->removeColumn('color')
		        ->make(true);
	}

	public function getCustomers(Request $request){
        $search = strtoupper($request->input('q'));
        $data = CustomerBranch::select([
        	'id',
        	DB::raw("'[ '|| rut ||' | '|| code|| ' ] '|| label AS text")
        ])
        ->where('label', 'LIKE', "%{$search}%")
        ->orWhere('rut', 'LIKE', "%{$search}%")
        ->get();

        return response()->json($data);
    }
}
