@extends('helpers.modalForm', [
          'route' 		=> route('maintainer.security.role.store'),
          'title' 		=> "Crear rol",
          'titleInfo'	=> '',
          'actionClass' => 'add-record',
          'actionLabel' => 'Guardar',
          ])

@section('inputs')
 	@include('maintainer.security.role.form')
@endsection