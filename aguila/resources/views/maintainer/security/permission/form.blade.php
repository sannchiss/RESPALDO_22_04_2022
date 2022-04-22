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
<div class="form-row">
    <div class="form-check form-check-inline col-sm-10">
        <input class="form-check-input" type="checkbox" id="is_credential" value="1" name='is_credential' {{ $data->is_credential ? 'checked' : '' }} >
        <label class="form-check-label" for="is_credential">Credencial</label>
    </div>
</div>
<br>
<div class="form-row">
    @include('helpers.inputs', [
        'type'        => 'text', 
        'inputName'   => 'root_route', 
        'groupClass'  => 'col-sm-10'
    ])
</div>


<div class="form-row">
  <div class="form-group  col-sm-10">
    <label>Permisos</label>
    <div>
        @foreach($data->permissions as $key => $value)
            <div class="form-check form-check-inline col-sm-4">
                <input class="form-check-input" type="checkbox" id="check{{ $key }}" value="{{ $value['val'] }}" name='permissions[]' {{  $value['checked'] }}>
                <label class="form-check-label" for="check{{ $key }}">{{ __($key) }}</label>
            </div>
        @endforeach
    </div>
  </div>
</div>