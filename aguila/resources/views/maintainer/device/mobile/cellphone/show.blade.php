@extends('helpers.modalForm', [
          'title'       => 'Telefono celular',
          'titleInfo'   => 'ID#'.$data->id,
          'isForm'      => false,
          ])


@section('modal-content')
    @include('helpers.listGroup', [
        'list' => $data->getModelAttributes([
            'label'      => 'label',
            'imei'       => 'imei',
            'imsi'       => 'imsi',
            'phone_number'       => 'phone_number',
            'phone_operator'     => 'phone_operator_id',
            'cellphone_platform' => 'cellphone_platform_id',
            'os_version' => 'os_version',
            'office'     => 'office_id',
            'status'     => 'status_id',
            'fullname'   => 'employee_id',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ])
    ])
@endsection