@extends('helpers.modalForm', [
          'title'       => 'Transportista',
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