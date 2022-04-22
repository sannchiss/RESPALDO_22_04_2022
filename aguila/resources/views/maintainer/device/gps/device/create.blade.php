@extends('helpers.modalForm', [
          'route' 		=> route('maintainer.device.gps.device.store'),
          'title' 		=> "Crear dispositivo",
          'titleInfo'	=> '',
          'actionClass' => 'add-record',
          'actionLabel' => 'Guardar',
          ])

@section('inputs')
 	@include('maintainer.device.gps.device.form')
@endsection