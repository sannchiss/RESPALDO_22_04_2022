@extends('helpers.modalForm', [
          'route' 			 => route('maintainer.security.menu.store'),
          'title' 			 => "Crear menu item",
          'titleInfo'		 => '',
          'actionClass' 	 => 'add-record',
          'actionLabel' 	 => 'Guardar',
          'anotherActionTag' => "data-only-action = 'true' data-Action = 'refreshMenuIndex()'",
          ])

@section('inputs')
 	@include('maintainer.security.menu.form')
@endsection