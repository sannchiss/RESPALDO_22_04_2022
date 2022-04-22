<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'name', 
        'groupClass'  => 'col-sm-6'
    ])

    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'lastname', 
        'groupClass'  => 'col-sm-6'
    ])
</div>

<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'rut', 
        'groupClass'  => 'col-sm-5'
    ])
    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'dv', 
        'groupClass'  => 'col-sm-1'
    ])

    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'code', 
        'groupClass'  => 'col-sm-6'
    ])
</div>
<div class="form-row">
     @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'phone', 
        'groupClass'  => 'col-sm-6'
    ])
    @include('helpers.inputs', [
        'type'        => 'select', 
        'inputName'   => 'employee_type_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->employee_type_id,
        'optDatas'    => $employeeTypes,
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
        'inputName'   => 'status_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->status_id,
        'optDatas'    => $statuses,
    ])   
</div>

<div class="form-row">
    <div class="form-check form-check-inline col-sm-10">
        <input class="form-check-input" type="checkbox" id="has_access" value="1" name='has_access' {{ $data->has_access ? 'checked' : '' }} >
        <label class="form-check-label" for="has_access">Acceso al sistema</label>
    </div>
</div>