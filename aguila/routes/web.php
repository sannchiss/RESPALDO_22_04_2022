<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//uth::routes(['register' => false]);
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/', function () {
    return redirect('home');
});
Route::group(['middleware' => ['auth']], function () {
	Route::get('/home', 'HomeController@index')->name('home');
});
Route::group(['middleware' => ['auth','permission']], function () {

	
	Route::name('maintainer.')
		->namespace('Maintainer')
		->prefix('maintainer')
		->group(function(){

			Route::name('adjustment.')
				->namespace('Adjustment')
				->prefix('adjustment')
				->group(function(){
					Route::resource('route', 'RouteController');
				});

			Route::name('security.')
				->namespace('Security')
				->prefix('security')
				->group(function(){

					Route::resource('user', 'UserController');
					Route::resource('permission', 'PermissionController');
					Route::resource('role', 'RoleController');
					Route::resource('menu', 'MenuController');
				});

			Route::name('employee.')
				->namespace('Employee')
				->prefix('employee')
				->group(function(){
					Route::resource('type', 'EmployeeTypeController');
					Route::resource('employee', 'EmployeeController');
				});

			Route::name('office.')
				->namespace('Office')
				->prefix('office')
				->group(function(){
					Route::resource('group', 'OfficeGroupController');
					Route::resource('office', 'OfficeController');
				});

			Route::name('vehicle.')
				->namespace('Vehicle')
				->prefix('vehicle')
				->group(function(){
					Route::resource('type', 'VehicleTypeController');
					Route::resource('carrier', 'CarrierController');
					Route::resource('vehicle', 'VehicleController');
				});
			
			Route::name('device.')
				->namespace('Device')
				->prefix('device')
				->group(function(){

					Route::name('gps.')
						->namespace('Gps')
						->prefix('gps')
						->group(function(){
							Route::resource('type', 'GpsTypeController');
							Route::resource('device', 'GpsDevicesController');
						});

					Route::name('mobile.')
						->namespace('Mobile')
						->prefix('mobile')
						->group(function(){
							Route::resource('app', 'AppController');
							Route::resource('cellphone', 'CellphoneController');
						});
			});

			Route::name('system.')
				->namespace('System')
				->prefix('system')
				->group(function(){
					Route::resource('area', 'SystemAreaController');
					Route::resource('status', 'StatusController');
					Route::resource('status_reason', 'StatusReasonController');
				});
	});
	Route::name('monitoring.')
		->group(function(){
			Route::get('/monitoring', 'MonitoringController@index')->name('index');
			Route::get('/markers/{office_id}/{type}/{condition}', 'MonitoringController@markers')->name('markers');
			Route::get('/currentstatus/{type}/{id}/{office_id}', 'MonitoringController@currentStatus')->name('currentstatus');
			Route::post('/engineoffon', 'MonitoringController@engineOffOn')->name('engineoffon');
		});
	Route::name('pick_delivery.')
		->prefix('pick_delivery')
		->group(function(){
			Route::get('/customer_positions','PickDeliveryController@getCustomerPositions')->name('customer_positions');
			Route::get('/vehicle_position/{vehicle_id}','PickDeliveryController@getVehiclePosition')->name('vehicle_position');
			Route::get('/status','PickDeliveryController@index')->name('index');
			Route::get('/documents','PickDeliveryController@getDocuments')->name('documents');
			Route::get('/vehicle','PickDeliveryController@getVehicle')->name('vehicle');
			Route::get('/document-detail/{document_id}','PickDeliveryController@geDocumentDetail')->name('document_detail');
			Route::get('/document-images/{document_id}','PickDeliveryController@getDocumentImages')->name('document_images');
		});
	Route::name('quest.')
		->prefix('quest')
		->group(function(){
			Route::get('/','QuestController@index')->name('index');
			Route::get('/document-detail/{document_id}','QuestController@geDocumentDetail')->name('document_detail');
			Route::get('/document-images/{document_id}','QuestController@getDocumentImages')->name('document_images');
			Route::get('/customers','QuestController@getCustomers')->name('customers');
			Route::get('/vehicle_position/{vehicle_id}','QuestController@getVehiclePosition')->name('vehicle_position');
		});
	Route::name('home.')
		->prefix('home')
		->group(function(){
			Route::get('/sales','HomeController@sales')->name('sales');
		});

	Route::name('processing.')
		->prefix('processing')
		->group(function(){
			Route::get('/', 'ProcessingController@index')->name('index');
			Route::get('/patente', 'ProcessingController@patente')->name('patente');
			Route::get('/post_select', 'ProcessingController@post_select')->name('post_select');
			Route::post('/checkdocument', 'ProcessingController@checkdocument')->name('checkdocument');
		});

	Route::name('reports.')
		->namespace('Reports')
		->prefix('reports')
		->group(function(){
			Route::name('documents.')
			->prefix('documents')
			->group(function(){ 
			Route::get('/', 'ReportsController@index')->name('index');
			Route::get('/document', 'ReportsController@document')->name('document');
			Route::get('/document-detail/{document_id}','ReportsController@geDocumentDetail')->name('document_detail');
			Route::get('/document-images/{document_id}','ReportsController@getDocumentImages')->name('document_images');
			Route::get('/export/{date_start}/{date_end}','ReportsController@export')->name('export');
							});

			Route::name('vehicles.')
				->prefix('vehicles')
				->group(function(){
						Route::get('/', 'ReportVehiclesController@index')->name('index');
						Route::get('/document', 'ReportVehiclesController@document')->name('document');
						Route::get('/document-detail/{vehicle_id}/{date_start}/{date_end}', 'ReportVehiclesController@detailRouteVehicle')->name('route_detail');
					});
		});	

	Route::name('reverse.')
		->prefix('reverse')
		->group(function(){
			Route::get('/', 'ReverseController@index')->name('index');
			Route::get('document', 'ReverseController@document')->name('document');
			Route::get('routes','ReverseController@route')->name('routes');
			Route::get('reload','ReverseController@reload')->name('reload');
			Route::post('checkdocument','ReverseController@checkdocument')->name('checkdocument');
		
		});	

});

