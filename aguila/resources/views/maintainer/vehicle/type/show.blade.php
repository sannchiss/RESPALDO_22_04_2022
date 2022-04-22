@extends('helpers.modalForm', [
          'title'       => 'Tipo de vehiculo',
          'titleInfo'   => 'ID#'.$data->id,
          'isForm'      => false,
          ])
          
@section('modal-content')
    @include('helpers.listGroup', [
        'list' => $data->getModelAttributes([
            'code'  => 'code',
            'label' => 'label'
        ])
    ])
@endsection