<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuentas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use DataTables;

class CargaArchivoEmpresa extends Controller
{

    public function store(Request $request){
        $path = public_path().'\uploads';
            $doc = $request->file('file');
            //Log::info($path);

            $fileName = $doc->getClientOriginalName();
            Log::info($fileName);
  
          //Si no esta vacio el directorio muevo el archivo a carpeta Public
          $directorio = $doc->move($path, $fileName);

                //dd("subido y guardado");

                $carpeta = "uploads";
/*                  $file = fopen(public_path($carpeta."/".$fileName), 'r') or exit("Unable to open file!");
 */
                 //SE ABRE EL ARCHIVO EN MODO LECTURA
                $file = fopen(public_path($carpeta."\\".$fileName), 'r');


                 if( $file == false ) {
                    Log::info("Error abriendo el archivo");
                }
                  else
                  {   

                    Cuentas::truncate();

                    $data = array();

                    $arrayTxa = array();
                    $arrayGts = array();
                    $arrayEmp = array();

                    $acountUptd = new Cuentas;

                    $i = 1;
                    while(!feof($file)) {
                        
                        $data  = explode("|", fgets($file));
                        //SI QUEREMOS VER TODO EL CONTENIDO EN BRUTO:

                         $arrayTxa[$i]  = $data[0];
                         $arrayGts[$i]  = $data[1];
                         $arrayEmp[$i]  = $data[2];

                        
                        $i++; 
                        
                    }
                    fclose($file);

                    $respuesta = array();

                     for($k = 1; $k<=sizeof($arrayTxa); $k++){
                 
                        $respuesta[] = [
                            'cuenta_txa' => $arrayTxa[$k],
                            'cuenta_gts' => $arrayGts[$k],
                            'empresa'    => $arrayEmp[$k]
                        ];

                        //Log::debug("Longitud de array".$k);


                    }  

                    
                     foreach(array_chunk($respuesta,30, false) as $t) {

                        DB::table('cuentas')->insert($t);

                     } 
                    //Cuentas::insert($respuesta);


                        }


                return view('admin.add.empresa.index');
           
    
}

public function listCompany(Request $request){
 
    if($request->ajax()){ 
    
        $data = Cuentas::query()
        ->select([
            'cuentas.id AS id',
            'cuentas.cuenta_txa AS cuenta_txa',
            'cuentas.cuenta_gts AS cuenta_gts',
            'cuentas.empresa AS empresa',

        ])
        ->groupBy('cuentas.id', 'cuentas.cuenta_txa','cuentas.cuenta_gts', 'cuentas.empresa')
        ->orderByRaw('id');

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    
              $button = '<button type="button" name = "copy" class="btn btn btn-sm copy" data-id ="'.$data->id.'" data-txa ="'.$data->cuenta_txa.'" data-gts ="'.$data->cuenta_gts.'" data-empresa ="'.$data->empresa.'"><i class="now-ui-icons files_paper"></i><a><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
              <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
              <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
            </svg></a></button>';

                    return $button; 
                })
                ->rawColumns(['action'])
                ->make(true);
    
    
    }


}


}