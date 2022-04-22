<?php

namespace App\Http\Controllers\Maintainer\Device\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;
use App\Models\CellphonePlatform;
use App\Models\PhoneOperator;
use App\Models\Office;
use App\Models\Employee;
use App\Models\Status;
use DB;

class CellphoneController extends Controller
{
    use MaintainerControllers;

	protected $view			= 'maintainer.device.mobile.cellphone';
    protected $model 		= 'App\Models\Cellphone';
    protected $storeExcept  = [];
    protected $updateExcept = [];
    protected $avoidValidate = ['label','imei','imsi'];


    //@override
    protected function validationRules($request = null){
        return [
            'label'        			=> 'required|string|max:255|unique:cellphones',
            'imei'        			=> 'required|string|max:255|unique:cellphones',
            'imsi'        			=> 'required|string|max:255|unique:cellphones',
            'phone_number'          => 'required|numeric',
            'phone_operator_id'     => 'required|integer',
            'cellphone_platform_id' => 'required|integer',
            'os_version'   			=> 'required|string|max:255',
            'office_id'   			=> 'required|integer',
            'status_id'  			=> 'required|integer'
        ];
    }

    //@override
    protected function dataIndex()
    {
       $offices = \Auth::user()->getOffices();
       return  $this->model::query()
        ->join('cellphone_platforms','cellphone_platforms.id', '=', 'cellphones.cellphone_platform_id')
        ->join('offices','offices.id', '=', 'cellphones.office_id')
        ->leftjoin('employees','employees.id', '=', 'cellphones.employee_id')
        ->join('statuses','statuses.id', '=', 'cellphones.status_id')
        ->join('phone_operators','phone_operators.id', '=', 'cellphones.phone_operator_id')
        ->whereIn('cellphones.office_id', $offices->pluck('id'))
        ->select([
            'cellphones.id',
            'cellphones.label',
            'cellphones.imei',
            'cellphones.imsi',
            'cellphone_platforms.label as cellphone_platform',
            'offices.label AS office',
            'statuses.label AS status',
            DB::raw("CONCAT(employees.name,' ',employees.lastname) as fullname"),
            'cellphones.created_at',
            'cellphones.updated_at',
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
    protected function showData($id){
        return  $this->model::join('cellphone_platforms','cellphone_platforms.id', '=', 'cellphones.cellphone_platform_id')
        ->join('offices','offices.id', '=', 'cellphones.office_id')
        ->leftjoin('employees','employees.id', '=', 'cellphones.employee_id')
        ->join('statuses','statuses.id', '=', 'cellphones.status_id')
        ->join('phone_operators','phone_operators.id', '=', 'cellphones.phone_operator_id')
        ->where ('cellphones.id', $id)
        ->select([
            'cellphones.id',
            'cellphones.label',
            'cellphones.imei',
            'cellphones.imsi',
            'cellphones.os_version',
            'phone_operators.label AS phone_operator',
            'cellphones.phone_number',
            'cellphone_platforms.label as cellphone_platform',
            'offices.label AS office',
            'statuses.label AS status',
            DB::raw("CONCAT(employees.name,' ',employees.lastname) as fullname"),
            'cellphones.created_at',
            'cellphones.updated_at',
        ])
        ->first();
    }

    //@override
    protected function createEditData($data = null){
        $employee_id = $data->employee_id ?? -999;
    	$employees =  Employee::leftJoin('cellphones', 'cellphones.employee_id', 'employees.id')
            ->join('employee_types', function($join){
                $join
                    ->on('employee_types.id', '=', 'employees.employee_type_id')
                    ->whereIn('employee_types.code', ['DRIVER']);
            })
            ->whereNull('cellphones.employee_id')
            ->orWhere('cellphones.employee_id', $employee_id)
            ->select([
                'employees.id',
                DB::raw("CONCAT(employees.name,' ',employees.lastname) as label")
            ])
            ->get();

        return [
        	'phoneOperators' 	 => PhoneOperator::get(),
            'cellphonePlatforms' => CellphonePlatform::get(),
            'employees'			 => $employees,
            'statuses'           => Status::getByArea('CELLPHONE'),
            'offices'       	 => Office::get(),
        ];
    }
}