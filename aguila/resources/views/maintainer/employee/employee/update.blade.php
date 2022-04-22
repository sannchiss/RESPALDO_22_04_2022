@extends('helpers.modalForm', [
          'route'		=> route('maintainer.employee.employee.update',[$data->id]),
          'title' 		=> "Actualizar empleado",
          'titleInfo'   => $data->label,
          'actionClass' => "update-record",
          'actionLabel' => "Guardar",
          ])

@section('inputs')
  @include('maintainer.employee.employee.form')
@endsection


