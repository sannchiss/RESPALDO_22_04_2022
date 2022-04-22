@extends('helpers.modalForm', [
          'route' 		=> route('maintainer.security.permission.store'),
          'title' 		=> "Crear permiso",
          'titleInfo'	=> '',
          'actionClass' => 'add-record',
          'actionLabel' => 'Guardar',
          ])

@section('inputs')
 	@include('maintainer.security.permission.form')
@endsection