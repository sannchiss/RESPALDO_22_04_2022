@extends('helpers.modalForm', [
          'title'       => 'Dispositivo',
          'titleInfo'   => 'ID#'.$data->id,
          'isForm'      => false,
          ])


@section('modal-content')
    @include('helpers.listGroup', [
        'list' => $data->getModelAttributes([
            'type'       => 'gps_type_id',
            'version'    => 'version',
            'imei'       => 'imei',
            'imsi'       => 'imsi',
            'phone_number' => 'phone_number',
            'phone_operator' => 'phone_operator_id',
            'vehicle'    => 'vehicle_id',
            'status'     => 'status_id',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ])
    ])
@endsection