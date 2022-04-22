@extends('helpers.modalForm', [
          'route'		=> route('maintainer.device.mobile.app.update',[$data->id]),
          'title' 		=> "Actualizar aplicacion",
          'titleInfo'   => $data->label,
          'actionClass' => "update-record",
          'actionLabel' => "Guardar",
          ])

@section('inputs')
  @include('maintainer.device.mobile.app.form')
@endsection


