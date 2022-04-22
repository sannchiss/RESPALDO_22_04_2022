@extends('helpers.modalForm', [
          'route'		=> route('maintainer.device.mobile.cellphone.update',[$data->id]),
          'title' 		=> "Actualizar telefono celular",
          'titleInfo'   => $data->label,
          'actionClass' => "update-record",
          'actionLabel' => "Guardar",
          ])

@section('inputs')
  @include('maintainer.device.mobile.cellphone.form')
@endsection


