<?php

namespace App\Http\Controllers\Maintainer\Security;

use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;
use Illuminate\Http\Request;
use Validator;
use App\Models\Role;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    use MaintainerControllers; 
    protected $view = 'maintainer.security.user';
    protected $model = 'App\User';
    protected $storeExcept = [];
    protected $updateExcept = ['employee_id'];
    protected $avoidValidate = [];

    //@override
    protected function dataIndex()
    {
       $offices = \Auth::user()->getOffices();
       $rol = \Auth::user()->role()->first()->code;
       return  $this->model::query()
        ->join('roles', function($join) use($rol){
            $join->on('roles.id', '=', 'users.role_id');
            if($rol != 'ADMIN' ){
               $join->where('code','!=','ADMIN');
            }
        })
        //->join('roles','roles.id', '=', 'users.role_id')
        //->join('employees','employees.id', '=', 'users.employee_id')
        ->join('employees', function($join) use($offices){
            $join->on('employees.id', '=', 'users.employee_id')
                ->whereIn('employees.office_id', $offices->pluck('id'));
        })
        ->select([
            'users.id',
            \DB::raw("CONCAT(employees.name,' ',employees.lastname) as fullname"),
            'email',
            'label',
            'users.created_at',
            'users.updated_at',
        ]);

    }
    //@override
    protected function methodDataTable($DataTables){
        return $DataTables
                    ->filterColumn('fullname', function($query, $keyword) {
                        $sql = "lower(CONCAT(employees.name,' ',employees.lastname))  like lower(?)";
                        $query->whereRaw($sql, ["%{$keyword}%"]);
                    });
    }

    //@override
    protected function createEditData($data = null){
        $employees =  Employee::leftJoin('users', 'users.employee_id', 'employees.id')
                                ->where('employees.has_access', true)
                                ->whereNull('users.email')
                                ->select('employees.*')
                                ->get();

        if(\Auth::user()->role()->first()->code == 'ADMIN'){
            $roles = Role::get();
        } else {
            $roles = Role::where('code','!=','ADMIN')->get();
        }

        return [
            'roles'     => $roles, 
            'employees' => $employees
        ];

    }

    //@override
    protected function avoidValidationRules($model = null, $request = null){

        $toValidate =  [
            'email'       => 'required|string|email|max:255|unique:users',
            'password'    => 'required|string|min:6|confirmed',
            'role_id'     => 'required|integer',
            'employee_id' => 'required|integer|unique:users',
        ];

        //Update validation
        if(!is_null($model)){
            if( $request->input('email') == $model->email){
                unset($toValidate['email']);
            }
            if($request->input('role_id') == $model->role_id){
                unset($toValidate['role_id']);
            }
            if($request->input('password') == ''){
                unset($toValidate['password']);
            }
            if(is_null($request->input('employee_id', null))){
                unset($toValidate['employee_id']);
            }
        }

        return $toValidate;
    }

    //@override
    protected function showData($id){
        return  $this->model::join('roles', 'roles.id', 'users.role_id')
                        ->join('employees', 'employees.id', 'users.employee_id')
                        ->where([
                            ['users.id', '=', $id]
                        ])->first();
    }

    //Metodo de guardado de la vista crear
    public function store(Request $request)
    {
        Validator::make(
            $request->all(), 
            $this->avoidValidationRules(null, $request)
        )->validate();
        
        $inputs = $request->except($this->storeExcept);
        $inputs['password'] = Hash::make($inputs['password']);
        $data = $this->model::create($inputs);
        $this->alterStore($data, $request);
    }

     //Metodo para guardar la edicion de la vista edit
    public function update(Request $request, $id)
    {   
        $model          = $this->model::find($id);
        $validateFlieds = $this->avoidValidationRules($model, $request);
        Validator::make($request->all(), $validateFlieds )->validate();

        //Si no viene password exepcionalo (Solo se usa con UserController)
        if(array_key_exists('password', $validateFlieds)){
            $inputs = $request->except($this->updateExcept);
            $inputs['password'] = Hash::make($inputs['password']);
        }
        else {
            $inputs = $request->except(array_merge($this->updateExcept,['password']));
        }
        $data = $model->update($inputs);        
    }
}