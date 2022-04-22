@extends('helpers.modalForm', [
          'route' 		=> route('maintainer.vehicle.carrier.store'),
          'title' 		=> "Crear transportista",
          'titleInfo'	=> '',
          'actionClass' => 'add-record',
          'actionLabel' => 'Guardar',
          ])

@section('inputs')
 	@include('maintainer.vehicle.carrier.form')
@endsection