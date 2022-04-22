@extends('helpers.modalForm', [
          'route'		=> route('maintainer.adjustment.route.update',[$data->id]),
          'title' 		=> "Actualizar vehiculo en ruta",
          'titleInfo'   => $data->route,
          'actionClass' => "update-record",
          'actionLabel' => "Guardar",
          ])

@section('inputs')
  @include('maintainer.adjustment.route.form')
@endsection


