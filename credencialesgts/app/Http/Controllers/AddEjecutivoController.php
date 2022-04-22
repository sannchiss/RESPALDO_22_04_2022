<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Empresas;
use App\Models\Ejecutivos;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Log;
use DataTables;

class AddEjecutivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       /// Log::info("Listar Ejecutivo");
        if($request->ajax()){

            $ejecutivo =  Ejecutivos::select('id', 'name')->get();
           // Log::info($ejecutivo);
            
            $data = Ejecutivos::query()
            ->select([
            'id AS id',
            'name AS name',
            'phone AS phone',
            'email AS email'
    
            ]);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                   
                    $button = '<button type="button" name = "eliminar" class="btn btn-danger btn-sm eliminarEjecutivo" data-id ="'.$data->id.'"><i class="now-ui-icons files_paper"></i><a>Eliminar</a></button>';
                   
                    return $button; 
                })
                ->rawColumns(['action'])
                ->make(true);
        
        
        }        
    }

    public function add(Request $request){
      
        $ejecutivo = Ejecutivos::create(
            ['name'             => $request['nombre'],
            'phone'              => $request['telefono'],
            'email'             => $request['email'],
            'remember_token'    => $request['remember_token']
        ]);
        return $ejecutivo;



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
