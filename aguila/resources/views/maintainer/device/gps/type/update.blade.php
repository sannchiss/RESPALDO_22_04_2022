@extends('helpers.modalForm', [
          'route'		=> route('maintainer.device.gps.type.update',[$data->id]),
          'title' 		=> "Actualizar tipo de gps",
          'titleInfo'   => $data->label,
          'actionClass' => "update-record",
          'actionLabel' => "Guardar",
          ])

@section('inputs')
  @include('maintainer.device.gps.type.form')
@endsection


