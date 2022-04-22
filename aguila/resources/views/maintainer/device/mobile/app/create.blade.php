@extends('helpers.modalForm', [
          'route' 		=> route('maintainer.device.mobile.app.store'),
          'title' 		=> "Crear aplicacion",
          'titleInfo'	=> '',
          'actionClass' => 'add-record',
          'actionLabel' => 'Guardar',
          ])

@section('inputs')
 	@include('maintainer.device.mobile.app.form')
@endsection