@extends('helpers.modalForm', [
          'route' 		=> route('maintainer.system.status.store'),
          'title' 		=> "Crear estatus",
          'titleInfo'	=> '',
          'actionClass' => 'add-record',
          'actionLabel' => 'Guardar',
          ])

@section('inputs')
 	@include('maintainer.system.status.form')
@endsection