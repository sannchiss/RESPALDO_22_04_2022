@extends('helpers.modalForm', [
          'route' 		=> route('maintainer.office.group.store'),
          'title' 		=> "Crear grupos de oficinas",
          'titleInfo'	=> '',
          'actionClass' => 'add-record',
          'actionLabel' => 'Guardar',
          ])

@section('inputs')
 	@include('maintainer.office.group.form')
@endsection