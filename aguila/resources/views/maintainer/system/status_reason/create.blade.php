@extends('helpers.modalForm', [
          'route' 		=> route('maintainer.system.status_reason.store'),
          'title' 		=> "Crear rason de estado",
          'titleInfo'	=> '',
          'actionClass' => 'add-record',
          'actionLabel' => 'Guardar',
          ])

@section('inputs')
 	@include('maintainer.system.status_reason.form')
@endsection