@extends('helpers.modalForm', [
          'title'       => 'Razon de Estato',
          'titleInfo'   => 'ID#'.$data->id,
          'isForm'      => false,
          ])
          
@section('modal-content')
    @include('helpers.listGroup', [
        'list' => $data->getModelAttributes([
            'area'  => 'system_area_id',
            'status'  => 'status_id',
            'label' => 'label',
            'color' => 'color',
        ])
    ])
@endsection