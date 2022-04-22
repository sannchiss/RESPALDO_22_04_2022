@extends('helpers.modalForm', [
          'route'		=> route('maintainer.office.office.update',[$data->id]),
          'title' 		=> "Actualizar oficina",
          'titleInfo'   => $data->label,
          'actionClass' => "update-record",
          'actionLabel' => "Guardar",
          ])

@section('inputs')
  @include('maintainer.office.office.form')
@endsection


