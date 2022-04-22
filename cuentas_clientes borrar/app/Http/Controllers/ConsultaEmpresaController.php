<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Empresas;
use App\Models\Ejecutivos;
use App\Models\Usuarios;
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

        $company_id = $request->input('id');
        Log::info($company_id);
    if($request->ajax()){ 
    
     //$data = DocumentarEnvio::latest()->get();

     $data = Usuarios::query()
     ->join('empresas AS company','company.id', '=', 'usuarios.id_company')
     ->where('usuarios.id_company', '=', $company_id)
     ->select([
         'usuarios.id AS id',
         'company.name AS company',
         'usuarios.name AS name',
         'usuarios.email AS email',
         'usuarios.password AS password',
         'usuarios.modality AS modality',
         'usuarios.created_at AS created_at'
     ])
     ->groupBy('usuarios.id', 'company.name', 'usuarios.name', 'usuarios.email', 'usuarios.password', 'usuarios.created_at', 'usuarios.modality')
     ->orderByRaw('id');


     return Datatables::of($data)
         ->addIndexColumn()
         ->addColumn('action', function($data){
             $button = '<button type="button" name = "Ver" class="btn btn-success btn-sm mostrarCredencial" data-id ="'.$data->id.'"><i class="now-ui-icons files_paper"></i><a>Ver</a></button>';
             $button .= ' <button type="button" name = "Edita" class="btn btn-danger btn-sm" id ="'.$data->id.'"><i class="now-ui-icons ui-1_simple-remove"></i>Editar</button>';
             $button .= ' <button type="button" name = "Eliminar" class="btn btn-danger btn-sm" id ="'.$data->id.'"><i class="now-ui-icons ui-1_simple-remove"></i>Eliminar</button>';

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
