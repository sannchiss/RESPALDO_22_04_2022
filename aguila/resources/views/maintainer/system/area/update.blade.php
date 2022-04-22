@extends('helpers.modalForm', [
          'route'		=> route('maintainer.system.area.update',[$data->id]),
          'title' 		=> "Actualizar area",
          'titleInfo'   => $data->label,
          'actionClass' => "update-record",
          'actionLabel' => "Guardar",
          ])

@section('inputs')
  @include('maintainer.system.area.form')
@endsection


