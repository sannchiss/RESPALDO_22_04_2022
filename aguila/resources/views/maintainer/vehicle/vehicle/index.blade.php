@extends('layouts.app')

@section('content')
    @include('helpers.breadcrumb')
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h3 class="card-title">Vehiculos 
                @if(Permission::hasPermission('create'))
                    <a type="button" class="btn btn-secondary btn-sm float-right show-form" href="{{ route('maintainer.vehicle.vehicle.create') }}">
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
                <th>Código</th>
                <th>Nombre</th>
                <th>Patente</th>
                <th>Tipo</th>
                <th>Condición</th>
                <th>Transportista</th>
                <th>Oficina</th>
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
            ajax: "{{ route('maintainer.vehicle.vehicle.index') }}",
            language: {
                "url": dtLanguage
            },
            columns: [
                {data: 'id',           name: 'id'},
                {data: 'code',         name: 'code'},
                {data: 'label',        name: 'label'},
                {data: 'plate_number', name: 'plate_number'},
                {data: 'type',         name: 'vehicle_types.label'},
                {data: 'condition',    name: 'vehicle_conditions.label'},
                {data: 'carrier',      name: 'carriers.label'},
                {data: 'office',       name: 'offices.label'},
                {data: 'created_at',   name: 'created_at'},
                {data: 'updated_at',   name: 'updated_at'},
                {data: 'action',       name: 'action', searchable: false, orderable: false},
            ]
        });
    });
</script>
@endsection