@extends('helpers.modalForm', [
          'route' 		=> route('maintainer.office.office.store'),
          'title' 		=> "Crear oficina",
          'titleInfo'	=> '',
          'actionClass' => 'add-record',
          'actionLabel' => 'Guardar',
          ])

@section('inputs')
 	@include('maintainer.office.office.form')
@endsection