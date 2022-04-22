<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'code', 
        'groupClass'  => 'col-sm-6'
    ])
    
    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'label', 
        'groupClass'  => 'col-sm-6'
    ])
</div>
<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'plate_number', 
        'groupClass'  => 'col-sm-6'
    ])

    @include('helpers.inputs', [
        'type'        => 'select', 
        'inputName'   => 'vehicle_type_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->vehicle_type_id,
        'optDatas'    => $vehicleTypes,
    ])  
</div>
<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'select', 
        'inputName'   => 'carrier_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->carrier_id,
        'optDatas'    => $carriers,
    ]) 
    @include('helpers.inputs', [
        'type'        => 'select', 
        'inputName'   => 'office_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->office_id,
        'optDatas'    => $offices,
    ]) 
</div>
<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'select', 
        'inputName'   => 'status_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $statusVehicle[0]->code,
        'optDatas'    => $statusVehicle,
    ]) 
    @include('helpers.inputs', [
        'type'        => 'select', 
        'inputName'   => 'vehicle_condition_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->vehicleCondition,
        'optDatas'    => $vehicleCondition,
    ]) 
</div>
