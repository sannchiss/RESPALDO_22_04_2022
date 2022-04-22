@extends('helpers.modalForm', [
          'route' 		=> route('maintainer.system.area.store'),
          'title' 		=> "Crear area",
          'titleInfo'	=> '',
          'actionClass' => 'add-record',
          'actionLabel' => 'Guardar',
          ])

@section('inputs')
 	@include('maintainer.system.area.form')
@endsection