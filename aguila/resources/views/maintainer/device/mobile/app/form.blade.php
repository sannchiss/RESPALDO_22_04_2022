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
        'inputName'   => 'latest_version_name', 
        'groupClass'  => 'col-sm-6'
    ])

    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'latest_version_code', 
        'groupClass'  => 'col-sm-6'
    ])
</div>
<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'previus_version_name', 
        'groupClass'  => 'col-sm-6'
    ])

    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'previus_version_code', 
        'groupClass'  => 'col-sm-6'
    ])
</div>
<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'select', 
        'inputName'   => 'active_update', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->active_update,
        'optDatas'    => $updateStatus,
    ])
    
    @include('helpers.inputs', [
        'type'        => 'select', 
        'inputName'   => 'cellphone_platform_id', 
        'groupClass'  => 'col-sm-6',
        'optId'       => 'id', 
        'optLabel'    => 'label',
        'optCompare'  => $data->cellphone_platform_id,
        'optDatas'    => $cellphonePlatforms,
    ])   
</div>