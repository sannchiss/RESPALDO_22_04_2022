<div class="form-row">
  @include('helpers.inputs', [
    'type'        => 'text', 
    'inputName'   => 'code', 
    'groupClass'  => 'col-sm-10'
  ])
</div>

<div class="form-row">
  @include('helpers.inputs', [
    'type'        => 'text', 
    'inputName'   => 'label', 
    'groupClass'  => 'col-sm-10'
  ])
</div>
  <div class="form-row offices-input">
    <label class="col-sm-12 special-invalid">Oficinas asociadas</label>
    <span class="invalid-feedback">
      <strong></strong>
    </span>
    @foreach($offices as $value)
      <div class="input-group col-sm-6" style="margin-bottom: 5px;">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <input type="checkbox" id="check-{{ $value->id }}" value="{{ $value->id }}" name='offices[]' {{  $value->checked }}>
          </div>
        </div>
        <label class="form-check-label form-control" for="check-{{ $value->id }}" style="height: auto;">
          <div class="">
            {{ $value->label }}
          </div>
        </label>
      </div>
    @endforeach
  </div>


  <div class="form-row permissions-input">
    <label class="col-sm-12 special-invalid">Permisos</label>
    <span class="invalid-feedback">
      <strong></strong>
    </span>
    @foreach($permissions as $value)
      <div class="input-group col-sm-6" style="margin-bottom: 5px;">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <input type="checkbox" id="check-{{ $value->id }}" value="{{ $value->id }}" name='permissions[]' {{  $value->checked }}>
          </div>
        </div>
        <label class="form-check-label form-control" for="check-{{ $value->id }}" style="height: auto;">
          <div class="">
            {{ $value->label }}
          </div>
          <div class="">
            @foreach($value->permissions as $key_permission => $permission)
              @if( $permission['checked'] == 'checked')
                <span class="badge badge-success">{{ __($key_permission) }}</span>
              @endif
            @endforeach

          </div>
        </label>
      </div>
    @endforeach
  </div>

