<?php

namespace App\Http\Controllers;
use App\Models\Usuarios;
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
    
    public function AgregarUsuario(Request $request)
    {

        $usuario = Usuarios::create(
                ['name'             => $request['name'],
                'user'              => $request['user'],
                'id_company'        => $request['id_company'],
                'email'             => $request['email'],
                'password'          => $request['password'],
                'modality'          => $request['modality'],
                'acountTXA'         => $request['acountTXA'],
                'acountGTS'         => $request['acountGTS'],
                'id_ejecutivo'      => "1",
                'remember_token'    => $request['remember_token']
            ]);
            return $usuario;
            //Le retorna en JSON el resultado de la insert
            //return "Empresa Registrada";


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
