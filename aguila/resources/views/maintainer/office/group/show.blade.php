@extends('helpers.modalForm', [
          'title'       => 'Grupo de oficinas',
          'titleInfo'   => 'ID#'.$data->id,
          'isForm'      => false,
          ])

@section('modal-content')
    @include('helpers.listGroup', [
        'list' => $data->getModelAttributes([
            'code'       => 'code',
            'label'      => 'label', 
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ])
    ])
@endsection