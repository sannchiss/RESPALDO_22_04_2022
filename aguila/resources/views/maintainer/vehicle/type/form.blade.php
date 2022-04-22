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