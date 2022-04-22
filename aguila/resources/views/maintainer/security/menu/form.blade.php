<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'label', 
        'groupClass'  => 'col-sm-5'
    ])

    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'icon', 
        'groupClass'  => 'col-sm-5'
    ])
</div>

<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'route', 
        'groupClass'  => 'col-sm-10'
    ])
</div>
<div class="form-row">
  @include('helpers.inputs', [
    'type'        => 'select', 
    'inputName'   => 'menu_id', 
    'groupClass'  => 'col-sm-6',
    'optId'       => 'id', 
    'optLabel'    => 'label',
    'optCompare'  => $data->menu_id,
    'optDatas'    => $menus,
  ])
</div>