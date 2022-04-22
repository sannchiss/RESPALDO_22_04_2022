@extends('helpers.modalForm', [
          'title'       => 'Aplicacion',
          'titleInfo'   => 'ID#'.$data->id,
          'isForm'      => false,
          ])


@section('modal-content')
    @include('helpers.listGroup', [
        'list' => $data->getModelAttributes([
            'code'       => 'code',
            'label'      => 'label',
            'icon'       => 'icon',
            'cellphone_platform'   => 'cellphone_platform_id',
            'latest_version_name'  => 'latest_version_name',
            'previus_version_name' => 'previus_version_name',
            'latest_version_code'  => 'latest_version_code',
            'previus_version_code' => 'previus_version_code',
            'active_update' => 'active_update',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ])
    ])
@endsection