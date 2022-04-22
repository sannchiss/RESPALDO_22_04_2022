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

<div class="form-row offices-input">
    <label class="col-sm-12 special-invalid">Oficinas Asociadas</label>
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
            {{ $value->label }}
        </label>
      </div>
    @endforeach
    
</div>
