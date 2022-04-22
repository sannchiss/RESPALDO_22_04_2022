@extends('helpers.modalForm', [
          'title'       => 'Menu item',
          'titleInfo'   => 'ID#'.$data->id,
          'isForm'      => false,
          ])

@section('modal-content')
    @include('helpers.listGroup', [
        'list' => $data->getModelAttributes([
            'label'      => 'label',
            'icon'       => 'icon',
            'menu_id'    => 'menu_id',
            'route'      => 'route', 
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ])
    ])
@endsection