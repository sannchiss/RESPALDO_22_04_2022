@extends('helpers.modalForm', [
          'route'		=> route('maintainer.security.role.update',[$data->id]),
          'title' 		=> "Actualizar rol",
          'titleInfo'   => $data->label,
          'actionClass' => "update-record",
          'actionLabel' => "Guardar",
          ])

@section('inputs')
  @include('maintainer.security.role.form')
@endsection


