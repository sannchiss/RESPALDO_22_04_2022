@extends('helpers.modalForm', [
          'title'       => 'Area',
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