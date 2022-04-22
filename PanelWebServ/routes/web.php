<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrearEnvioController;
use App\Http\Controllers\DetalleExpedicionController;
use App\Http\Controllers\AnularEnvioController;
use App\Http\Controllers\EntregaRecogedor;
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



Route::group([], function()  
{  
   Route::get('/',function(){  
  
   return view('admin.crearEnvio.createsend');  
    
 });  
Route::get('/shipping',function()  
 {  
    return view('admin.mostrarEnvio.shipping');  
 });  
Route::get('/management',function()  
 {  
    return view('admin.gestionEntrega.management');  
 }); 
 
 Route::get('/detailLabel',function()  
 {  
    return view('admin.detalleExpedicion.detailExp');  
 }); 
 

});  


Route::name('processing.')
->prefix('processing')
->group(function(){
   
   Route::get('/', [CrearEnvioController::class,'index'])->name('index');

   Route::post('crearenvio', [CrearEnvioController::class,'index'])->name('crearenvio');
   Route::get('consultaenvio', [CrearEnvioController::class,'query'])->name('consultaenvio');
   Route::get('detalleExp', [DetalleExpedicionController::class,'index'])->name('detalleExp');
   Route::get('muestraRuta', [DetalleExpedicionController::class,'showRoute'])->name('muestraRuta');
   Route::get('anularenvio', [AnularEnvioController::class,'index'])->name('anularenvio');
   Route::get('etiquetapdf', [CrearEnvioController::class,'etiquetaPDF'])->name('etiquetapdf');



   /*Route::post('crearenvio', 'CrearEnvioController@index')->name('crearenvio');
   Route::get('consultaenvio', 'CrearEnvioController@query')->name('consultaenvio');
   Route::get('detalleExp','DetalleExpedicionController@index')->name('detalleExp');
   Route::get('muestraRuta','DetalleExpedicionController@showRoute')->name('muestraRuta');
   Route::get('anularenvio', 'AnularEnvioController@index')->name('anularenvio');
   Route::get('etiquetapdf', 'CrearEnvioController@etiquetaPDF')->name('etiquetapdf');*/
});

Route::name('management.')
->prefix('management')
->group(function(){

   Route::get('/management', [EntregaRecogedor::class,'index'])->name('index');                 // Ruta listar los envios
   Route::post('/delivery', [EntregaRecogedor::class,'deliveryManagement'])->name('delivery');   // Entrega de envios

/*
   Route::get('/management', 'EntregaRecogedor@index')->name('index'); // Ruta listar los envios 
   Route::post('/delivery', 'EntregaRecogedor@deliveryManagement')->name('delivery'); // Entrega de envios 
*/
});

