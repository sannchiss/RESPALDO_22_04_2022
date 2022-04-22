<div class="form-row">
  @include('helpers.inputs', [
    'type'        => 'text', 
    'inputName'   => 'email', 
    'groupClass'  => 'col-sm-6'
  ])
  @include('helpers.inputs', [
    'type'        => 'password', 
    'inputName'   => 'password', 
    'groupClass'  => 'col-sm-6'
    ])

</div>

<div class="form-row">
  @include('helpers.inputs', [
    'type'        => 'select', 
    'inputName'   => 'role_id', 
    'groupClass'  => 'col-sm-6',
    'optId'       => 'id', 
    'optLabel'    => 'label',
    'optCompare'  => $data->role_id,
    'optDatas'    => $roles,
  ])
  @include('helpers.inputs', [
    'type'        => 'password', 
    'inputName'   => 'password_confirmation', 
    'groupClass'  => 'col-sm-6'
  ])
</div>

@if($data->employee_id == '')
  <div class="form-row">
    @include('helpers.inputs', [
      'type'        => 'select', 
      'inputName'   => 'employee_id', 
      'groupClass'  => 'col-sm-12',
      'optId'       => 'id',
      'optLabel'    => 'full_name',
      'optCompare'  => $data->employee_id,
      'optDatas'    => $employees,
    ])
  </div>
@endif