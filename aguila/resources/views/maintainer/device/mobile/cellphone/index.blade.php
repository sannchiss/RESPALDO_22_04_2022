@extends('layouts.app')

@section('content')
    @include('helpers.breadcrumb')
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h3 class="card-title">Telefonos celulares 
                @if(Permission::hasPermission('create'))
                    <a type="button" class="btn btn-secondary btn-sm float-right show-form" href="{{ route('maintainer.device.mobile.cellphone.create') }}">
                        <i class="fas fa-plus" aria-hidden="true"></i></span> Nuevo
                    </a>
                @endif
            </h3>
            
            <div class="clearfix"></div>
        </div>
        <div class="card-body">
            <table id="index-table" class="table table-striped">
            <thead class="thead-dark">
              <tr>
                <th>#ID</th>
                <th>Nombre</th>
                <th>IMEI</th>
                <th>IMSI</th>
                <th>OS</th>
                <th>Oficina</th>
                <th>Empleado</th>
                <th>Estado</th>
                <th>Creado</th>
                <th>Actualizado</th>
                <th></th>
              </tr>
            </thead>
        </table>
        </div>
    </div>
    @include('helpers.modal',['id' => 'modal-form','class'=>'modal-lg'])
    @include('helpers.modalDelete')
@endsection

@section('script')
<script type="text/javascript">
    var table;
    $(document).ready(function() {
        table = $('.table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('maintainer.device.mobile.cellphone.index') }}",
            language: {
                "url": dtLanguage
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'label', name: 'cellphones.label'},
                {data: 'imei',  name: 'cellphones.imei'},
                {data: 'imsi',  name: 'cellphones.imsi'},
                {data: 'cellphone_platform', name: 'cellphone_platforms.label'},
                {data: 'office', name: 'offices.label'},
                {data: 'fullname', name: 'fullname'},
                {data: 'status', name: 'statuses.label'},
                {data: 'created_at', name: 'created_at'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action',    name: 'action', searchable: false, orderable: false},
            ]
        });
    });
</script>
@endsection