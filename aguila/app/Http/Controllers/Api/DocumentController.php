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

class DocumentController extends Controller
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


        \Log::info('Cargando ruta numero ->'.$routeNumber);
        $routeExist = $this->validateRoute($routeNumber, $office, $company,$originalCompany);
        if($routeExist){
            \Log::info('ruta numero ->'.$routeNumber.' Ya se encuentra cargada');
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
            $documents,
            $office->id,
            $company,
            $originalCompany
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

        $routeExist = $this->validateRoute($routeNumber, $office, $company,$originalCompany);
        if($routeExist){
            \Log::info('ruta numero ->'.$routeNumber.' Ya se encuentra cargada');
            return response()->json("Ruta {$routeNumber} cargada anteriormente", 422);
        }
        
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
    private function preparedocuments($documents,$office_id,$company,$originalCompany){
        $countOrder            = 0;
        $quantityRoutePackages = 0;
        $quantityRouteUnits    = 0;
        $routeProducts         = [];
        $modelDocuments        = [];
        foreach ($documents as $key => $document) {
            if(!$this->isValidOrder($document, $key)){
                $this->documentError++;
                continue;
            }
            $rutSeller = $document['rutSalesMan'] ?? null;
            $dvSeller  = $document['dvSalesMan'] ?? null;
            $seller = Employee::findByCodeOrCreate($document['codSalesMan'], 'SELLER', $office_id, $document['namSalesMan'],null,$rutSeller, $dvSeller);

            /* Encuentro o creo clientes */
            //si es prologic dejar codigo de la empresa(numeros hasta 999)
            //de lo contrario el rut
            $customerCode   = $company == 4000 ? $originalCompany : $document['cRut'];
            $customerLabel  = $document['cName'];
            $customer = Customer::findByCodeOrCreate($customerCode, $customerCode, $document['cName'], $office_id);
            
            $customerBranchCode = $document['cCCost'];//para ambos casos el branch code es el centro de costo
            
            $customerBranchRut  = $document['cRut'];

            $customerBranchData = [
                'label' => $document['cName'],
                'rut'   => $customerBranchRut,
                'seller_id' => $seller->id,
                'phone'   => $document['cPhone'],
                'address' => $document['cDir'],
                'lat'     => $document['cY'],
                'lon'     => $document['cX'],
                'email'   => $document['Customer_email'] ?? null,
            ];

            //envio el modelo completo de customer
            try {
                $customerBranch = CustomerBranch::findByCodeOrCreate($customer->id, $customerBranchCode, $customerBranchData, $document['cCommune']);
            } catch (\Exception $e) {
                $paramsError = [$customer->id, $customerBranchCode, $customerBranchData, $document['cCommune']];
                \Log::info('ERROR CREAR CUSTOMER ', $paramsError);
                continue;
            }
            

            //preparo los items o detalles del docuemnto
            $details = $this->prepareDetails($document['items'],$routeProducts);

            $modelDocuments[$countOrder] = new Document();
            $modelDocuments[$countOrder]->document_type_id   = $document['isDelivery'];//1 Entrega : 0 Retiro
            $modelDocuments[$countOrder]->code               = $document['guideNumber']; //numero de documento
            $modelDocuments[$countOrder]->document_date      = \DateTime::createFromFormat('d/m/Y', $document['guideDate'])->format('Y-m-d');
            $modelDocuments[$countOrder]->order_number       = $document['orderNumber'];
            $modelDocuments[$countOrder]->order_barcode      = $document['orderNumber'];
            $modelDocuments[$countOrder]->status_id          = Status::findByCode('DOCUMENT','PENDING_DEPARTURE')->id;
            $modelDocuments[$countOrder]->seller_id          = $seller->id;
            $modelDocuments[$countOrder]->row_order          = $document['rowOrder']; //bultos
            $modelDocuments[$countOrder]->packages           = $document['orderBulk']; //bultos
            $modelDocuments[$countOrder]->products           = $details['documentProducts'];
            $modelDocuments[$countOrder]->units              = $details['totalUnits'];
            $modelDocuments[$countOrder]->customer_branch_id = $customerBranch->id;

            //almaceno los detalles
            $modelDocuments[$countOrder]->details            = $details['modelDocumentDetail'];

            $countOrder += 1;
            $quantityRoutePackages += $document['orderBulk'];
            $quantityRouteUnits    += $details['totalUnits'];

        }

        return [
            'quantityRouteProducts' => count($routeProducts),
            'quantityRouteUnits'    => $quantityRouteUnits,
            'quantityRoutePackages' => $quantityRoutePackages,
            'modelDocuments'        => $modelDocuments       
        ];

    }

    /* Comienza guardado de items ó Detalle de documento */
    private function prepareDetails($details, $routeProducts){
        $countItem  = 0;
        $totalUnits = 0;
        $documentProducts = [];

        foreach ($details as $key => $detail) {
            $modelDocumentDetail[$countItem] = new DocumentDetail();
            $modelDocumentDetail[$countItem]->status_id  = Status::findByCode('DOCUMENT_DETAIL','PENDING_DEPARTURE')->id;
            $modelDocumentDetail[$countItem]->product_id = Product::findByCodeOrCreate($detail['codItem'],$detail['desItem'])->id;
            $modelDocumentDetail[$countItem]->quantity   = $detail['cantItem'];
            $modelDocumentDetail[$countItem]->row_order  = $detail['itemLine'];
            $countItem += 1;
            $documentProducts[$detail['codItem']] = null;
            $routeProducts[$detail['codItem']]    = null;
            $totalUnits += $detail['cantItem'];
        }

        return [
            'routeProducts'       => $routeProducts,
            'documentProducts'    => count($documentProducts),
            'totalUnits'          => $totalUnits,
            'modelDocumentDetail' => $modelDocumentDetail,
        ];
    }

    private function validateRoute($routeNumber, $office, $company, $originalCompany){
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
        return $routeExist;
    }

	private function isValidOrder($document, $key){

		$validator = \Validator::make($document, [
            'cRut' 	   => 'required',
            'cCCost'   => 'required',
            'cName'    => 'required',
            'cDir'     => 'required',
            'cCommune' => 'required',
            'cRegion'  => 'required',
            'codSalesMan' => 'required',
            'namSalesMan' => 'required',
            'guideNumber' => 'required|numeric',
            'orderNumber' => 'required|numeric',
            'isDelivery'  => 'required|boolean',
            'guideDate'   => 'required|date_format:"d/m/Y"',
            'rowOrder'    => 'required|numeric',
            'orderBulk'   => 'required|numeric',
            'items'		  => 'required|array',
            'items.*.codItem' => 'required',
            'items.*.desItem' => 'required',
            'items.*.cantItem' => 'required|integer|min:1',
            'items.*.itemLine' => 'required|numeric',
        ]);

        if ($validator->fails()) {
        	$this->errors[] = ['key' => $key, 'errors' =>  $validator->errors()->all()];
        	return false;
        }

        return true;



	}

}