@extends('helpers.modalForm', [
          'route' 		=> route('maintainer.device.mobile.cellphone.store'),
          'title' 		=> "Crear telefono celular",
          'titleInfo'	=> '',
          'actionClass' => 'add-record',
          'actionLabel' => 'Guardar',
          ])

@section('inputs')
 	@include('maintainer.device.mobile.cellphone.form')
@endsection