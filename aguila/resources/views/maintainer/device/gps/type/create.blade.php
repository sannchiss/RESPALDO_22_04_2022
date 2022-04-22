@extends('helpers.modalForm', [
          'route' 		=> route('maintainer.device.gps.type.store'),
          'title' 		=> "Crear tipo de gps",
          'titleInfo'	=> '',
          'actionClass' => 'add-record',
          'actionLabel' => 'Guardar',
          ])

@section('inputs')
 	@include('maintainer.device.gps.type.form')
@endsection