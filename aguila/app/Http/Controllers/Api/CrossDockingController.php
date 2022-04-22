<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Employee;
use App\Models\Carrier;
use App\Models\Office;
use App\Models\Route;
use App\Models\Document;
use App\Models\DocumentDetail;
use App\Models\Customer;
use App\Models\CustomerBranch;
use App\Models\Status;
use App\Models\Product;

class CrossDockingController extends Controller
{
	private $errors = []; 
    private $documentError;
    private $documentCount;
    private $modelDocuments = [];

	public  function store(Request $request){

	   
		\Validator::make($request->all(), [
            'company' 	   => 'required|numeric',
            'routeNumber'  => 'required|numeric',
            'routeDate'	   => 'required|date_format:"d/m/Y"',
            'movil'		   => 'required|numeric',
            'plate'		   => 'required',
            'codDriver'	   => 'required|numeric',
            'namDriver'	   => 'required',
            'codCarriage'  => 'required|numeric',
            'compCarriage' => 'required',
            'order'		   => 'required|array',
        ])->validate();

		$originalCompany = $request->input('company');
        $prilogicCompany  = $request->input('cCode', 0);
		$routeNumber  = $request->input('routeNumber');
		$routeDate    = $request->input('routeDate');
		$movil 		  = $request->input('movil');
		$plate		  = $request->input('plate');
		$codDriver    = $request->input('codDriver');
		$namDriver 	  = $request->input('namDriver');
        $rutDriver    = $request->input('rutDriver',null);
        $digDriver    = $request->input('digDriver',null);
		$codAux 	  = $request->input('codAux',null);
		$namAux   	  = $request->input('namAux','');
        $rutAux       = $request->input('rutAux',null);
        $digAux       = $request->input('digAux',null);
		$codCarriage  = $request->input('codCarriage');
		$compCarriage = $request->input('compCarriage');
		$documents 	  = $request->input('order');


        //Ajusto compañia a prilogic 
        $company = intval($originalCompany) <= 999 ? 4000 : $originalCompany;
        //manejo nuevo campo cCode = 0 para empresas que no son prilogic 
        $originalCompany = $prilogicCompany == 0 ? $originalCompany : $prilogicCompany;
        /* Traigo o Guardado officina, conductor, vehiculo, transportista */
        $office = Office::findByCodeOrCreate($company);


        \Log::info('Cargando crossdocking ruta numero ->'.$routeNumber);
        if($company != 4000){
            $routeExist = Route::where([['code', '=',$routeNumber],['office_id','=', $office->id]])->exists();
        } else { //validaciond de repetido prilogic
            $routeExist = Route::join('documents', 'routes.id', '=', 'documents.route_id')
                            ->join('customer_branches', 'customer_branches.id', '=', 'documents.customer_branch_id')
                            ->join('customers', function($join) use($originalCompany){
                                $join->on('customers.id', '=', 'customer_branches.customer_id')
                                    ->on('customers.code', '=', \DB::raw("'{$originalCompany}'"));
                            })
                            ->where([['routes.code', '=',$routeNumber],['routes.office_id','=', $office->id]])
                            ->exists();
        }

        if($routeExist){
            \Log::info('ruta crossdocking numero ->'.$routeNumber.' Ya se encuentra cargada');
            return response()->json("Ruta {$routeNumber} cargada anteriormente", 422);
        }
		
        $carrier        = Carrier::findByCodeOrCreate($codCarriage, $compCarriage);
        $driver         = Employee::findByCodeOrCreate($codDriver, 'DRIVER', $office->id, $namDriver, null,$rutDriver,$digDriver);
        $auxiliary_id   = is_null($codAux) ? $codAux : Employee::findByCodeOrCreate($codAux, 'AUXILIARY', $office->id, $namAux,null,$rutAux,$digAux)->id;
		$vehiculo       = Vehicle::findByCodeOrCreate($movil, $plate, $movil, $carrier->id, $office->id,  $driver->id);
		/* fin */

		$this->documentCount = count($documents);
		$this->documentError = 0;

        $preparedocuments = $this->preparedocuments(
            $documents
        );

        if(count($preparedocuments['modelDocuments']) < 1){
            return response()->json(array_merge(['global_error' => 'sin documentos que cargar'], $this->errors), 422);
        }
        
        $route = new Route();
        $route->code = $routeNumber;
        $route->vehicle_id      = $vehiculo->id;
        $route->driver_id       = $driver->id;
        $route->auxiliary_id    = $auxiliary_id;
        $route->scheduled_date  = \DateTime::createFromFormat('d/m/Y', $routeDate)->format('Y-m-d');
        $route->office_id       = $office->id;
        $route->status_id       = Status::findByCode('ROUTE','PENDING_DEPARTURE')->id;
        $route->viewing_time    = 0;
        $route->loaded_packages = $preparedocuments['quantityRoutePackages'];
        $route->loaded_products = $preparedocuments['quantityRouteProducts'];
        $route->loaded_units    = $preparedocuments['quantityRouteUnits'];
        $route->remainder_packages = 0;
        $route->remainder_products = 0;
        $route->remainder_units    = 0;
        $route->save();

        foreach ($preparedocuments['modelDocuments'] as $key => $modelDocument) {
            $modelDocument->route_id = $route->id;
            $modelDocument->save();
            $modelDocument->documentDetails()->saveMany($modelDocument->details);
        }        
        
        \Log::info('Fin de carga ruta numero ->'.$routeNumber);
		return response()->json('OK', 200);
	}

     /* Comienza guardado de orden ó Documento */
    private function preparedocuments($documents){
        $countOrder            = 0;
        $quantityRoutePackages = 0;
        $quantityRouteUnits    = 0;
        $routeProducts         = [];
        $modelDocuments        = [];
        foreach ($documents as $key => $document) {

            //preparo los items o detalles del docuemnto
           
            $officeOrigin = Office::findByCodeOrCreate('1000');
            $documentClone = Document::join('routes', 
            	function($join) use($officeOrigin){
            		$join->on('documents.route_id','=','routes.id');
            		//	->where('routes.office_id','=',$officeOrigin->id);
            	})
            ->where([
            	['documents.order_number','=',$document['orderNumber']],
            	['documents.code','=',$document['guideNumber']],
            ])
            ->select([
            	'documents.*',
            ])
            ->first();

            if(is_null($documentClone)){
            	\Log::info("CROSSDOCKING ERROR: Documento no encontrado pedido: {$document['orderNumber']} guia: {$document['guideNumber']}");
            	continue;
            }

            $details = $this->prepareDetails($documentClone,$routeProducts);

            $modelDocuments[$countOrder] = new Document();
            $modelDocuments[$countOrder]->document_type_id   = $documentClone->document_type_id;//1 Entrega : 0 Retiro
            $modelDocuments[$countOrder]->code               = $document['guideNumber']; //numero de documento
            $modelDocuments[$countOrder]->document_date      = $documentClone->document_date;
            $modelDocuments[$countOrder]->order_number       = $document['orderNumber'];
            $modelDocuments[$countOrder]->order_barcode      = $document['orderNumber'];
            $modelDocuments[$countOrder]->status_id          = Status::findByCode('DOCUMENT','PENDING_DEPARTURE')->id;
            $modelDocuments[$countOrder]->seller_id          = $documentClone->seller_id;
            $modelDocuments[$countOrder]->row_order          = $documentClone->row_order; //bultos
            $modelDocuments[$countOrder]->packages           = $documentClone->packages; //bultos
            $modelDocuments[$countOrder]->products           = $documentClone->products;
            $modelDocuments[$countOrder]->units              = $documentClone->units;
            $modelDocuments[$countOrder]->customer_branch_id = $documentClone->customer_branch_id;
            $modelDocuments[$countOrder]->origin_document_id = $documentClone->id;

            //almaceno los detalles
            $modelDocuments[$countOrder]->details            = $details['modelDocumentDetail'];

            $countOrder += 1;
            $quantityRoutePackages += $documentClone->packages;
            $quantityRouteUnits    += $documentClone->units;

        }

        return [
            'quantityRouteProducts' => count($routeProducts),
            'quantityRouteUnits'    => $quantityRouteUnits,
            'quantityRoutePackages' => $quantityRoutePackages,
            'modelDocuments'        => $modelDocuments       
        ];

    }

    /* Comienza guardado de items ó Detalle de documento */
    private function prepareDetails($documentClone, $routeProducts){
        $countItem  = 0;
        $totalUnits = 0;
        $documentProducts = [];

        $details = DocumentDetail::where('document_id','=',$documentClone->id)->get();
        foreach ($details as $key => $detail) {
            $modelDocumentDetail[$countItem] = new DocumentDetail();
            $modelDocumentDetail[$countItem]->status_id  = Status::findByCode('DOCUMENT_DETAIL','PENDING_DEPARTURE')->id;
            $modelDocumentDetail[$countItem]->product_id = $detail->product_id;
            $modelDocumentDetail[$countItem]->quantity   = $detail->quantity;
            $modelDocumentDetail[$countItem]->row_order  = $detail->row_order;
            $countItem += 1;
            $documentProducts[$detail->product_id] = null;
            $routeProducts[$detail->product_id]    = null;
            $totalUnits += $detail->quantity;
        }

        return [
            'routeProducts'       => $routeProducts,
            'documentProducts'    => count($documentProducts),
            'totalUnits'          => $totalUnits,
            'modelDocumentDetail' => $modelDocumentDetail,
        ];
    }

}
