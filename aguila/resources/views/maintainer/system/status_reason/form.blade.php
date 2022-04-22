<div class="form-row"> 
    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'label', 
        'groupClass'  => 'col-sm-6'
    ])
    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'color', 
        'groupClass'  => 'col-sm-6'
    ])
</div>
<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'select', 
        'inputName'   => 'system_area_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->system_area_id,
        'optDatas'    => $systemAreas,
    ])
    @include('helpers.inputs', [
        'type'        => 'select', 
        'inputName'   => 'status_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->status_id ?? '',
        'optDatas'    => $statuses,
    ])
</div>