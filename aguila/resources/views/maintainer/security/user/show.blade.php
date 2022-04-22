@extends('helpers.modalForm', [
          'title'       => 'Usuario',
          'titleInfo'   => 'ID#'.$data->id,
          'isForm'      => false,
          ])

@section('modal-content')
    @include('helpers.listGroup', [
        'list' => $data->getModelAttributes([
            'email'      => 'email',
            'label'      => 'role_id',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ])
    ])
   
@endsection