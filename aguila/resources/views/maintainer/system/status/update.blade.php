@extends('helpers.modalForm', [
          'route'		=> route('maintainer.system.status.update',[$data->id]),
          'title' 		=> "Actualizar estatus",
          'titleInfo'   => $data->label,
          'actionClass' => "update-record",
          'actionLabel' => "Guardar",
          ])

@section('inputs')
  @include('maintainer.system.status.form')
@endsection


