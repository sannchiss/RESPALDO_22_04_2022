@extends('helpers.modalForm', [
          'route'		=> route('maintainer.employee.type.update',[$data->id]),
          'title' 		=> "Actualizar tipo de empleado",
          'titleInfo'   => $data->label,
          'actionClass' => "update-record",
          'actionLabel' => "Guardar",
          ])

@section('inputs')
  @include('maintainer.employee.type.form')
@endsection


