@extends('helpers.modalForm', [
          'title'       => 'Vehiculo',
          'titleInfo'   => 'ID#'.$data->id,
          'isForm'      => false,
          ])
          
@section('modal-content')
    @include('helpers.listGroup', [
        'list' => $data->getModelAttributes([
            'code'         => 'code',
            'label'        => 'label',
            'plate_number' => 'plate_number',
        ])
    ])
@endsection