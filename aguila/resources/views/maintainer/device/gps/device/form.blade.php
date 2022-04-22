<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'select', 
        'inputName'   => 'gps_type_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->gps_type_id,
        'optDatas'    => $gpsTypes,
    ])
    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'version', 
        'groupClass'  => 'col-sm-6'
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
        'inputName'   => 'vehicle_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->vehicle_id,
        'optDatas'    => $vehicles,
    ])
    @include('helpers.inputs', [
        'type'        => 'select', 
        'inputName'   => 'status_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->statuses_id,
        'optDatas'    => $statuses,
    ])   
</div>