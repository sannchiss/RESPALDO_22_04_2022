@extends('helpers.modalForm', [
          'route'		=> route('maintainer.vehicle.vehicle.update',[$data->id]),
          'title' 		=> "Actualizar vehiculo",
          'titleInfo'   => $data->label,
          'actionClass' => "update-record",
          'actionLabel' => "Guardar",
          ])

@section('inputs')
  @include('maintainer.vehicle.vehicle.form')
@endsection