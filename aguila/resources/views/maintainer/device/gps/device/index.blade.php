@extends('layouts.app')

@section('content')
    @include('helpers.breadcrumb')
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h3 class="card-title">Dispositivos 
                @if(Permission::hasPermission('create'))
                    <a type="button" class="btn btn-secondary btn-sm float-right show-form" href="{{ route('maintainer.device.gps.device.create') }}">
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
                <th>Tipo</th>
                <th>Version</th>
                <th>IMEI</th>
                <th>IMSI</th>
                <th>Operador telefonico</th>
                <th>Nuemero de telefono</th>
                <th>Vehiculo</th>
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
            ajax: "{{ route('maintainer.device.gps.device.index') }}",
            language: {
                "url": dtLanguage
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'type', name: 'gps_types.label'},
                {data: 'version', name: 'version'},
                {data: 'imei', name: 'imei'},
                {data: 'imsi', name: 'imsi'},
                {data: 'phone_operator', name: 'phone_operators.label'},
                {data: 'phone_number', name: 'phone_number'},
                {data: 'vehicle', name: 'vehicles.label'},
                {data: 'status', name: 'status'},
                {data: 'created_at', name: 'created_at'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action',    name: 'action', searchable: false, orderable: false},
            ]
        });
    });
</script>
@endsection