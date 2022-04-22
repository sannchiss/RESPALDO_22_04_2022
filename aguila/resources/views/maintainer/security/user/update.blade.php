@extends('helpers.modalForm', [
          'route' 		=> route('maintainer.security.user.update',[$data->id]),
          'title' 		=> "Actualizar usuario",
          'titleInfo'   => $data->email,
          'actionClass' => 'update-record',
          'actionLabel' =>  'Guardar',
          ])

@section('inputs')
  @include('maintainer.security.user.form')
@endsection


