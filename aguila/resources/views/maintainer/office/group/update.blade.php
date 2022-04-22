@extends('helpers.modalForm', [
          'route'		=> route('maintainer.office.group.update',[$data->id]),
          'title' 		=> "Actualizar grupo de oficinas",
          'titleInfo'   => $data->label,
          'actionClass' => "update-record",
          'actionLabel' => "Guardar",
          ])

@section('inputs')
  @include('maintainer.office.group.form')
@endsection


