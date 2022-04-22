<?php
namespace App\Http\Traits;

use Illuminate\Http\Request;
use DataTables;
use Validator;

trait MaintainerControllers {

	/*
     * Complementary methods for http request methods
     * for usage override this methods on controllers
     */
    protected function dataIndex(){
    	return $this->model::query()->orderBYDesc('created_at');
    }
    protected function methodDataTable($DataTables){
        return $DataTables;
    }

    protected function createEditData($data = null){
        return [];
    }
    protected function validationRules($request = null){
        return [];
    }
    protected function showData($id){
        return $this->model::find($id);
    }
    protected function alterUpdate($data, $request){}
    protected function alterStore($data, $request){}

    //funcion transitoria necesaria para sacar 
    //las validaciones de update
    protected function avoidValidationRules($model = null, $request = null){
        //trae las validaciones
        $toValidate = $this->validationRules($request);

        //Update validation
        if(!is_null($model)){
            //Validaciones dinamicas
            foreach ($this->avoidValidate as $field) {
                if( $request->input($field) == $model->{$field}){
                    unset($toValidate[$field]);
                }
            }
        }

        return $toValidate;
    }

    /*
     * Beging Http request methods
     * if you need this can be override on controller 
     */

    //Genera un index default para los mantenedores
	public function index(Request $request)
    {
        if(!$request->ajax()){
            return view("{$this->view}.index");
        }
        //Si necesito llenar un selectButton
        if(isset($request->selectId)){
            return $this->model::selectOption($request->selectId);
        }

        $DataTables = DataTables::of($this->dataIndex());
        $DataTables = $this->methodDataTable($DataTables);

        return $DataTables->addColumn('action', function ($data) {
                return view('helpers.actionButtons',[
                    'show' => ['edit', 'view', 'delete'],
                    'updateRoute' => "{$this->view}.edit",
                    'showRoute'   => "{$this->view}.show",
                    'deleteRoute' => "{$this->view}.destroy",
                    'id' => $data->id]);
            })->make(true);
    }

    //retorna la vista por defecto para crear
    public function create()
    {
        $data = new $this->model; 
        return view("{$this->view}.create", 
            array_merge(['data' => $data], $this->createEditData($data))
        );
    }

    //Metodo de guardado de la vista crear
    public function store(Request $request)
    {
        Validator::make(
            $request->all(), 
            $this->avoidValidationRules(null, $request)
        )->validate();
        
        $data = $this->model::create($request->except($this->storeExcept));
        $this->alterStore($data, $request);
    }

    //Retorna una vista con los datos de un registro
    public function show($id)
    {
        return view("{$this->view}.show",['data' => $this->showData($id)]);
    }

    //Devuelve la vista para la edicion
    public function edit($id)
    {
        $data = $this->model::find($id);
        if(isset($data->password)){
            $data->password = '';
        }
        
        return view("{$this->view}.update",
            array_merge(['data' => $data], $this->createEditData($data))
        );
    }

    //Metodo para guardar la edicion de la vista edit
    public function update(Request $request, $id)
    {
        $model          = $this->model::find($id);
        $validateFlieds = $this->avoidValidationRules($model, $request);

        Validator::make($request->all(), $validateFlieds )->validate();

        $data = $model->update($request->except($this->updateExcept));
        $this->alterUpdate($model, $request);
        
    }

    //Elimina un registro
    public function destroy($id)
    {
        $this->model::destroy($id);
    }

}