@extends('helpers.modalForm', [
          'route' 		=> route('maintainer.vehicle.vehicle.store'),
          'title' 		=> "Crear vehiculo",
          'titleInfo'	=> '',
          'actionClass' => 'add-record',
          'actionLabel' => 'Guardar',
          ])

@section('inputs')
 	@include('maintainer.vehicle.vehicle.form')
@endsection