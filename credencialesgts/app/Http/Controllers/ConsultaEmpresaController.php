<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Empresas;
use App\Models\Ejecutivos;
use App\Models\Usuarios;
use App\Models\Cuentas;
use App\Models\CuentasUsuarios;
use DataTables;

use Illuminate\Support\Facades\Log;

class ConsultaEmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ConsultaEmpresa(Request $request)
    {

        $empresa = $request->input('empresa');
    if($request->ajax()){ 
    
     $consulta = $empresa =='empresa' ? 'null': $empresa;
     Log::info($consulta);

     if($consulta!= 'null'){ 
         
     $data = Usuarios::query()
     ->join('cuentas_usuarios AS company','company.id_usuario', '=', 'usuarios.id')
     ->whereRaw("company.empresa = '{$consulta}'")
     ->select([
         'usuarios.id AS id',
         'company.empresa AS company',
         'usuarios.name AS name',
         'usuarios.user AS user',
         'usuarios.email AS email',
         'usuarios.password AS password',
         'usuarios.modality AS modality',
         'usuarios.created_at AS created_at'
     ])
     ->groupBy('usuarios.id', 'company.empresa', 'usuarios.name','usuarios.user','usuarios.email', 'usuarios.password', 'usuarios.created_at', 'usuarios.modality')
     ->orderByRaw('id');
     }else
    {
        $data = Usuarios::query()
            ->select([
                'usuarios.id AS id',
                'usuarios.name AS name',
                'usuarios.user AS user',
                'usuarios.email AS email',
                'usuarios.password AS password',
                'usuarios.modality AS modality',
                'usuarios.created_at AS created_at'
            ])
            ->groupBy('usuarios.id', 'usuarios.name','usuarios.user','usuarios.email', 'usuarios.password', 'usuarios.created_at', 'usuarios.modality')
            ->orderByRaw('id');


    }

     return Datatables::of($data)
         ->addIndexColumn()
         ->addColumn('action', function($data){
            $button = '<button type="button" name = "add" class="btn btn btn-sm addAcount" data-id ="'.$data->id.'"><i class="now-ui-icons files_paper"></i><a><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-node-plus-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M11 13a5 5 0 1 0-4.975-5.5H4A1.5 1.5 0 0 0 2.5 6h-1A1.5 1.5 0 0 0 0 7.5v1A1.5 1.5 0 0 0 1.5 10h1A1.5 1.5 0 0 0 4 8.5h2.025A5 5 0 0 0 11 13zm.5-7.5a.5.5 0 0 0-1 0v2h-2a.5.5 0 0 0 0 1h2v2a.5.5 0 0 0 1 0v-2h2a.5.5 0 0 0 0-1h-2v-2z"/>
          </svg></a></button>';
            $button .='<button type="button" name = "list" class="btn btn listAcount" data-id ="'.$data->id.'"><i class="now-ui-icons files_paper"></i><a><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-card-checklist" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
            <path fill-rule="evenodd" d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>
          </svg></a></button>';
            $button .='<button type="button" name = "list" class="btn btn editInfo" data-id ="'.$data->id.'"><i class="now-ui-icons files_paper"></i><a><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
          </svg></a></button>';

             return $button; 
         })
         ->rawColumns(['action'])
         ->make(true);


        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
