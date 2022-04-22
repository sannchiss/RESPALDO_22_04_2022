<?php

namespace App\Http\Controllers\Maintainer\Office;

use App\Http\Controllers\Controller;
use App\Http\Traits\MaintainerControllers;
use App\Models\Office;
use DB;

class OfficeGroupController extends Controller
{
    use MaintainerControllers;

	protected $view			= 'maintainer.office.group';
    protected $model 		= 'App\Models\OfficeGroup';
    protected $storeExcept  = ['offices'];
    protected $updateExcept = ['offices'];
    protected $avoidValidate = ['label', 'code'];

    //@override
    protected function validationRules($request = null){

        return [
            'code'    => 'required|string|max:255|unique:office_groups',
            'label'   => 'required|string|max:255|unique:office_groups',
            'offices' => 'required',
        ];
    }

    //@override
    protected function createEditData($data = null){

        $idOfficeGroup = is_null($data->id) ? -9999 : $data->id;

        $offices = Office::leftJoin(
            'office_office_groups', 
            function ($leftJoin) use ($idOfficeGroup) {
                $leftJoin->on('office_office_groups.office_id', '=', 'offices.id')
                         ->on('office_office_groups.office_group_id', '=', DB::raw($idOfficeGroup));
            })
            ->select([
                'offices.id', 
                'offices.label',
                DB::raw("(CASE WHEN office_group_id is null THEN '' ELSE 'checked' END) AS checked")
            ])
            ->get();

        return ["offices" => $offices];
    }

    //@override
    protected function alterStore($data, $request){
        $offices = $request->input('offices',[]);
        $data->offices()->sync($offices);
    }

    //@override
    protected function alterUpdate($data, $request){
        $offices = $request->input('offices',[]);
        $data->offices()->sync($offices);
    }
}
