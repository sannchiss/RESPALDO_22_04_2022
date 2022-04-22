@extends('helpers.modalForm', [
          'route' 		=> route('maintainer.employee.type.store'),
          'title' 		=> "Crear tipo de empleado",
          'titleInfo'	=> '',
          'actionClass' => 'add-record',
          'actionLabel' => 'Guardar',
          ])

@section('inputs')
 	@include('maintainer.employee.type.form')
@endsection