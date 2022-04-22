@extends('helpers.modalForm', [
          'route'       => route('maintainer.security.user.store'),
          'title'       => "Crear usuario",
          'titleInfo'   => '',
          'actionClass' => 'add-record',
          'actionLabel' =>  'Guardar',
          ])

@section('inputs')
  @include('maintainer.security.user.form')
@endsection