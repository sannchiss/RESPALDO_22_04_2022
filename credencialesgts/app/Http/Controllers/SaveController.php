<?php

namespace App\Http\Controllers;
use App\Models\Usuarios;
use App\Models\CuentasUsuarios;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent;

class SaveController extends Controller
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
    
    public function agregarUsuario(Request $request)
    {

        $usuario = Usuarios::create(
                ['name'             => $request['name'],
                'user'              => $request['user'],
                'email'             => $request['email'],
                'password'          => $request['password123'],
                'modality'          => $request['modality'],
                'id_ejecutivo'      => $request['ejecutivo'],
                'remember_token'    => $request['remember_token']
            ]);
            return $usuario;
            //Le retorna en JSON el resultado de la insert
            //return "Empresa Registrada";

    }

    public function agregarCuentaUsuario(Request $request)
    { 
        $usuarios_cuentas = CuentasUsuarios::create([
            'id_usuario' => $request['idUser'],
            'empresa'    => $request['hiddeEmpresa'],
            'cuenta_txa' => $request['acountTXA'],
            'cuenta_gts' => $request['acountGTS']
        ]);
        return $usuarios_cuentas;

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
