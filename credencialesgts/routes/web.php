<?php

use Illuminate\Support\Facades\Route;

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
  
   return view('admin.panel.index');  
    
 });  

 Route::get('/query',function(){  
  
    return view('admin.panel.consultas.index');  
     
  });  

  Route::get('/executive',function(){  
  
   return view('admin.add.ejecutivo.index');  
    
 });  

 Route::get('/company', function(){
    return view('admin.add.empresa.index');
 });


}); 


Route::name('processing.')
->prefix('processing')
->group(function(){
   Route::get('/', 'CredencialesController@index')->name('usuarios');
   Route::get('credenciales', 'CredencialesController@credenciales')->name('credenciales');
   Route::get('empresas', 'ProcesamientoController@selectEmpresas')->name('empresas');
   Route::get('usuarioEmpresas', 'ProcesamientoController@selectEmpresasConsulta')->name('usuarioEmpresas');
   Route::get('ejecutivos', 'ProcesamientoController@selectEjecutivos')->name('ejecutivos');
   Route::get('detalleUsuario', 'ProcesamientoController@detalleUsuario')->name('detalleUsuario');
   Route::get('listaCuentas', 'ProcesamientoController@listaCuentas')->name('listaCuentas');
   Route::post('editarUsuario', 'ProcesamientoController@editarUsuario')->name('editarUsuario');
   Route::delete('eliminarCuenta', 'ProcesamientoController@eliminarCuenta')->name('eliminarCuenta');
   Route::delete('eliminarEjecutivo', 'ProcesamientoController@eliminarEjecutivo')->name('eliminarEjecutivo');


});

Route::name('save.')
->prefix('save')
->group(function(){
   Route::post('agregarUsuario', 'SaveController@agregarUsuario')->name('agregarUsuario');
   Route::post('agregarCuentaUsuario', 'SaveController@agregarCuentaUsuario')->name('agregarCuentaUsuario');

});

Route::name('query.')
->prefix('query')
->group(function(){
   Route::get('consultaEmpresa', 'ConsultaEmpresaController@ConsultaEmpresa')->name('consultaEmpresa');
});


Route::name('executive.') 
->prefix('executive')
->group(function(){
   Route::get('listexecutive', 'AddEjecutivoController@index')->name('listexecutive');
   Route::post('addexecutive', 'AddEjecutivoController@add')->name('addexecutive');

});

Route::name('upload.') 
->prefix('upload')
->group(function(){
Route::post('file', 'CargaArchivoEmpresa@store')->name('file');;
Route::get('loading','CargaArchivoEmpresa@listCompany')->name('loading');
});