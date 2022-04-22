<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Empresas;
use App\Models\Ejecutivos;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Log;

class ProcesamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function selectEmpresas(Request $request)
    {
        Log::info("Controlador select empresas");
        $company =  Empresas::select('id', 'name')->get();
        Log::info($company);
        
        return  ['company' => $company];

    }

    public function selectEjecutivos(Request $request)
    {

        Log::info("Controlador select ejecutivos");
        $ejecutivo =  Ejecutivos::select('id', 'name')->get();
        Log::info($ejecutivo);
        
        return  ['ejecutivo' => $ejecutivo];

    }

    
    public function detalleUsuario(Request $request)
    {
        Log::info("Detalle de usuario");
        $credencial_id = $request->input('Item_id');


        $credencialDetalle = Usuarios::query()
        ->join('empresas AS company','company.id', '=', 'usuarios.id_company')
        ->join('ejecutivos AS comercial','comercial.id', '=', 'usuarios.id_ejecutivo')
        ->where('usuarios.id', '=', $credencial_id)
        ->select([
            'usuarios.id AS id',
            'company.name AS company',
            'comercial.name AS comercial',
            'usuarios.name AS name',
            'usuarios.user AS user',
            'usuarios.email AS email',
            'usuarios.password AS password',
            'usuarios.modality AS modality',
            'usuarios.acountTXA AS acountTXA',
            'usuarios.acountGTS AS acountGTS',
            'usuarios.created_at AS created_at',
            'usuarios.updated_at AS updated_at'
        ])
        ->groupBy('usuarios.id', 'company.name','comercial.name', 'usuarios.name','usuarios.user', 'usuarios.email', 'usuarios.password','usuarios.modality','usuarios.acountTXA','usuarios.acountGTS','usuarios.created_at','usuarios.updated_at')
        ->orderByRaw('id')->get();

        return ['credencialDetalle' => $credencialDetalle];

        

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
