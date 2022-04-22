@extends('helpers.modalForm', [
          'route'			 => route('maintainer.security.menu.update',[$data->id]),
          'title' 			 => "Actualizar menu item",
          'titleInfo'   	 => $data->label,
          'actionClass' 	 => "update-record",
          'actionLabel' 	 => "Guardar",
          'anotherActionTag' => "data-only-action = 'true' data-Action = 'refreshMenuIndex()'",
          ])

@section('inputs')
  @include('maintainer.security.menu.form')
@endsection


