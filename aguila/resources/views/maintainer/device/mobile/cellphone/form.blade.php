<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'label', 
        'groupClass'  => 'col-sm-6'
    ])
    @include('helpers.inputs', [
        'type'        => 'select', 
        'inputName'   => 'status_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->status_id,
        'optDatas'    => $statuses,
    ])
</div>
<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'imei', 
        'groupClass'  => 'col-sm-6'
    ])

    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'imsi', 
        'groupClass'  => 'col-sm-6'
    ])
</div>
<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'phone_number', 
        'groupClass'  => 'col-sm-6'
    ])

    @include('helpers.inputs', [
        'type'        => 'select', 
        'inputName'   => 'phone_operator_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->phone_operator_id,
        'optDatas'    => $phoneOperators,
    ])
</div>
<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'select', 
        'inputName'   => 'cellphone_platform_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->cellphone_platform_id,
        'optDatas'    => $cellphonePlatforms,
    ])
    
    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'os_version', 
        'groupClass'  => 'col-sm-6'
    ])  
</div>
<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'select', 
        'inputName'   => 'office_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->office_id,
        'optDatas'    => $offices,
    ])
    
    @include('helpers.inputs', [
        'type'        => 'select', 
        'inputName'   => 'employee_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->employee_id,
        'optDatas'    => $employees,
    ]) 
</div>