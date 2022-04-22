@extends('helpers.modalForm', [
          'route' 		=> route('maintainer.vehicle.type.store'),
          'title' 		=> "Crear tipo de vehiculo",
          'titleInfo'	=> '',
          'actionClass' => 'add-record',
          'actionLabel' => 'Guardar',
          ])

@section('inputs')
 	@include('maintainer.vehicle.type.form')
@endsection