@extends('helpers.modalForm', [
          'route'		=> route('maintainer.vehicle.carrier.update',[$data->id]),
          'title' 		=> "Actualizar transportista",
          'titleInfo'   => $data->label,
          'actionClass' => "update-record",
          'actionLabel' => "Guardar",
          ])

@section('inputs')
  @include('maintainer.vehicle.carrier.form')
@endsection


