<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Cuentas;
use App\Models\Empresas;
use App\Models\Ejecutivos;
use App\Models\Usuarios;
use App\Models\CuentasUsuarios;
use DataTables;

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
        $company =  Cuentas::select('id','cuenta_txa','cuenta_gts','empresa')
        ->get();
        return response()->json($company);

    }
    public function selectEmpresasConsulta(Request $request){
        $companyUser =  CuentasUsuarios::select('empresa')
        ->groupBy('empresa')
        ->orderByRaw('empresa')
        ->get();
        return response()->json($companyUser);
    }

    public function selectEjecutivos(Request $request)
    {
        $ejecutivo =  Ejecutivos::select('id', 'name')->get();
      // return  ['ejecutivo' => $ejecutivo];
        return response()->json($ejecutivo);

    }

    
    public function detalleUsuario(Request $request)
    {
        $credencial_id = $request->input('Item_id');
        Log::info($credencial_id);
        $credencialDetalle = Usuarios::query()
        ->join('cuentas_usuarios AS company','company.id_usuario', '=', 'usuarios.id')
        ->join('ejecutivos AS comercial','comercial.id', '=', 'usuarios.id_ejecutivo')
        ->where('usuarios.id', '=', $credencial_id)
        ->select([
            'usuarios.id AS id',
            'company.empresa AS company',
            'comercial.id AS comercial_id',
            'comercial.name AS comercial',
            'usuarios.name AS name',
            'usuarios.user AS user',
            'usuarios.email AS email',
            'usuarios.password AS password',
            'usuarios.modality AS modality',
            'usuarios.created_at AS created_at',
            'usuarios.updated_at AS updated_at'
        ])
        ->groupBy('usuarios.id', 'company.empresa','comercial.id','comercial.name', 'usuarios.name','usuarios.user', 'usuarios.email', 'usuarios.password','usuarios.modality','usuarios.created_at','usuarios.updated_at')
        ->orderByRaw('id')->get();

        return ['credencialDetalle' => $credencialDetalle];
        

    }

        public function editarUsuario(Request $request){

            $id  = $request->input('hiddeUsuarioId');
            Log::info($id);

            if($request->input('modalidad') == "1")
            {$labelMod = 'B2B'; }else{$labelMod = 'B2C';}

            Usuarios::query()
            ->where('id', $id)
            ->update([
                'name' =>            $request->input('nombre'),
                'user' =>            $request->input('usuario'),
                'password' =>        $request->input('password'),
                'email' =>           $request->input('email'),
                'modality' =>        $labelMod,
                'id_ejecutivo' =>    $request->input('hiddeComercialId'),
            ]);
            
        }

        public function listaCuentas(Request $request){

        $user_id = $request->input('Item_id');
        if($request->ajax()){ 
        
         //$data = DocumentarEnvio::latest()->get();
    
         $data = CuentasUsuarios::query()
         ->join('usuarios AS usuariosCuentas','usuariosCuentas.id', '=', 'cuentas_usuarios.id_usuario')
         ->where('usuariosCuentas.id', '=', $user_id)
         ->select([
             'cuentas_usuarios.id AS id',
             'cuentas_usuarios.empresa AS empresa',
             'cuentas_usuarios.cuenta_txa AS cuenta_txa',
             'cuentas_usuarios.cuenta_gts AS cuenta_gts'
         ])
         ->groupBy('cuentas_usuarios.id','cuentas_usuarios.empresa', 'cuentas_usuarios.cuenta_txa', 'cuentas_usuarios.cuenta_gts')
         ->orderByRaw('cuentas_usuarios.id');
    
    
         return Datatables::of($data)
             ->addIndexColumn()
             ->addColumn('action', function($data){
                 $button = ' <button type="button" name = "Eliminar" class="btn btn-danger btn-sm delete" data-id ="'.$data->id.'"><i class="now-ui-icons ui-1_simple-remove"></i>Eliminar</button>';
                 return $button; 
             })
             ->rawColumns(['action'])
             ->make(true);
    
    
            }
    

        }

        public function eliminarCuenta(Request $request){

            $id = $request->input('Item_id');
           
                $acount   = CuentasUsuarios::findOrFail( $id );

                if ( $acount->delete() ) {
                    return response()->json( [
                        'success' => true,
                        'message' => '¡Cuenta Eliminada.',
                    ] );
                } else {
                    return response()->json( [
                        'success' => false,
                        'message' => '¡Error!, No se pudo eliminar.',
                    ] );
                }
        }    

        public function eliminarEjecutivo(Request $request){

            $id = $request->input('Item_id');
            Log::info($id);
           
                $acount   = Ejecutivos::findOrFail( $id );

                if ( $acount->delete() ) {
                    return response()->json( [
                        'success' => true,
                        'message' => '¡Cuenta Eliminada.',
                    ] );
                } else {
                    return response()->json( [
                        'success' => false,
                        'message' => '¡Error!, No se pudo eliminar.',
                    ] );
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
