@extends('helpers.modalForm', [
          'route'		=> route('maintainer.device.gps.device.update',[$data->id]),
          'title' 		=> "Actualizar dispositivo",
          'titleInfo'   => $data->imei,
          'actionClass' => "update-record",
          'actionLabel' => "Guardar",
          ])

@section('inputs')
  @include('maintainer.device.gps.device.form')
@endsection


