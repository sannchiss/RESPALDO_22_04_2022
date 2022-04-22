@extends('helpers.modalForm', [
          'title'       => 'Estatus',
          'titleInfo'   => 'ID#'.$data->id,
          'isForm'      => false,
          ])
          
@section('modal-content')
    @include('helpers.listGroup', [
        'list' => $data->getModelAttributes([
            'code'  => 'code',
            'label' => 'label',
            'color' => 'color',
            'area'  => 'system_area_id'
        ])
    ])
@endsection