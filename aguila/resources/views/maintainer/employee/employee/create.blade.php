@extends('helpers.modalForm', [
          'route' 		=> route('maintainer.employee.employee.store'),
          'title' 		=> "Crear empleado",
          'titleInfo'	=> '',
          'actionClass' => 'add-record',
          'actionLabel' => 'Guardar',
          ])

@section('inputs')
 	@include('maintainer.employee.employee.form')
@endsection