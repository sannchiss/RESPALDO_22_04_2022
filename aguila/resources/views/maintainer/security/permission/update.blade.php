@extends('helpers.modalForm', [
          'route'		=> route('maintainer.security.permission.update',[$data->id]),
          'title' 		=> "Actualizar permiso",
          'titleInfo'   => $data->label,
          'actionClass' => "update-record",
          'actionLabel' => "Guardar",
          ])

@section('inputs')
  @include('maintainer.security.permission.form')
@endsection


