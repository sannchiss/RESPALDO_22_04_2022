@extends('helpers.modalForm', [
          'route'		=> route('maintainer.vehicle.type.update',[$data->id]),
          'title' 		=> "Actualizar tipo de vehiculo",
          'titleInfo'   => $data->label,
          'actionClass' => "update-record",
          'actionLabel' => "Guardar",
          ])

@section('inputs')
  @include('maintainer.vehicle.type.form')
@endsection


