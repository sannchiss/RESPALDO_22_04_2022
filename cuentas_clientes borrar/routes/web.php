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




}); 


Route::name('processing.')
->prefix('processing')
->group(function(){
   Route::get('/', 'CredencialesController@index')->name('usuarios');
   Route::get('empresas', 'ProcesamientoController@selectEmpresas')->name('empresas');
   Route::get('ejecutivos', 'ProcesamientoController@selectEjecutivos')->name('ejecutivos');
   Route::get('detalleUsuario', 'ProcesamientoController@detalleUsuario')->name('detalleUsuario');
});

Route::name('save.')
->prefix('save')
->group(function(){
   Route::post('agregarUsuario', 'SaveController@AgregarUsuario')->name('agregarUsuario');
});

Route::name('query.')
->prefix('query')
->group(function(){
   Route::get('consultaEmpresa', 'ConsultaEmpresaController@ConsultaEmpresa')->name('consultaEmpresa');
});