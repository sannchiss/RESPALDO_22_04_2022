<?php

namespace App\Http\Controllers\Maintainer\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;
use App\Models\EmployeeType;
use App\Models\Office;
use App\Models\Status;
use Validator;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    use MaintainerControllers;

    protected $view			= 'maintainer.employee.employee';
    protected $model 		= 'App\Models\Employee';
    protected $storeExcept  = [];
    protected $updateExcept = [];
    protected $avoidValidate = ['rut', 'code'];

    //@override
    protected function validationRules($request = null){
        $employee_type_id = $request->input('employee_type_id');
        $rut = $request->input('rut');
        $dv = $request->input('dv');
        $office_id = $request->input('office_id');
        return [
        	'name'	   	  => 'required|string|max:255',
        	'lastname' 	  => 'required|string|max:255',
            'office_id'   => 'required|integer',
            'status_id'   => 'required|integer',
            'employee_type_id' => 'required|integer',
            'dv'           => 'required|string|max:1',
        	'rut' 		   => [
                'required',
                Rule::unique('employees')->where(function ($query) use($rut, $employee_type_id, $dv, $office_id) {
                    return $query->where('rut', $rut)
                    ->where('employee_type_id', $employee_type_id)
                    ->where('dv',$dv)
                    ->where('office_id',$office_id);
                }),'numeric'],
            'code'        => 'required|string|max:255|unique:employees,code,NULL,id,employee_type_id,'.$employee_type_id,
            'phone'       => 'required|numeric',
        ];
    }

    //@override
    protected function dataIndex()
    {
        $offices = \Auth::user()->getOffices();
       return  $this->model::query()
        ->join('employee_types','employee_types.id', '=', 'employees.employee_type_id')
        ->join('offices','offices.id','=','employees.office_id')
        ->join('statuses','statuses.id', '=','employees.status_id')
        ->whereIn('employees.office_id', $offices->pluck('id'))
        ->select([
            'employees.id',
            'employees.code',
            'statuses.label as status',
            \DB::raw("CONCAT(employees.rut,'-',employees.dv) as rut"),
            \DB::raw("CONCAT(employees.name,' ',employees.lastname) as fullname"),
            'employee_types.label as type',
            'offices.label AS office',
            'employees.created_at',
            'employees.updated_at',
        ]);
    }

    //@override
    protected function methodDataTable($DataTables){
        return $DataTables
                    ->filterColumn('rut', function($query, $keyword) {
                        $sql = "lower(CONCAT(employees.rut,'-',employees.dv))  like lower(?)";
                        $query->whereRaw($sql, ["%{$keyword}%"]);
                    })
                    ->filterColumn('fullname', function($query, $keyword) {
                        $sql = "lower(CONCAT(employees.name,' ',employees.lastname))  like lower(?)"; 
                        $query->whereRaw($sql, ["%{$keyword}%"]);
                    })
                    ->filterColumn('type', function($query, $keyword) {
                        $sql = "lower(employee_types.label)  like lower(?)";
                        $query->whereRaw($sql, ["%{$keyword}%"]);
                    })
                    ->filterColumn('status', function($query, $keyword) {
                        $sql = "lower(statuses.label)  like lower(?)";
                        $query->whereRaw($sql, ["%{$keyword}%"]);
                    })
                    ->filterColumn('office', function($query, $keyword) {
                        $sql = "lower(offices.label)  like lower(?)";
                        $query->whereRaw($sql, ["%{$keyword}%"]);
                    });
    }

    //@override
    protected function showData($id){
        return  $this->model::join('employee_types','employee_types.id', '=', 'employees.employee_type_id')
                        ->join('offices','offices.id','=','employees.office_id')
                        ->join('statuses','statuses.id', '=','employees.status_id')
                        ->where([
                            ['employees.id', '=', $id]
                        ])
                        ->select([
                            \DB::raw('employees.*'),
                            'employee_types.label as type',
                            'offices.label AS office',
                        ])
                        ->first();
    }

    //@override
    protected function createEditData($data = null){
        return [
            'employeeTypes' => EmployeeType::get(),
            'offices'       => Office::get(),
            'statuses'        => Status::getByArea('EMPLOYEE'),
        ];
    }

    //@override
    public function update(Request $request, $id)
    {
        $model          = $this->model::find($id);
        $validateFlieds = $this->avoidValidationRules($model, $request);

        Validator::make($request->all(), $validateFlieds )->validate();
        
        $toUpdate = $request->except($this->updateExcept);
        $toUpdate['has_access'] =  $request->input('has_access', false);

        $data = $model->update($toUpdate);
        
    }
}
