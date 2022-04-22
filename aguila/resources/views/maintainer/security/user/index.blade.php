@extends('layouts.app')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item">Seguridad</li>
            <li class="breadcrumb-item active">Usuarios</li>
        </ol>
    </nav>


    <div class="card">
        <div class="card-header bg-danger text-white">
            <h3 class="card-title">Usuarios
                @if(Permission::hasPermission('create'))
                    <a type="button" class="btn btn-secondary btn-sm float-right show-form" href="{{ route('maintainer.security.user.create') }}">
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
                <th>Email</th>
                <th>Perfil</th>
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
            ajax: "{{ route('maintainer.security.user.index') }}",
            language: {
                "url": "{{ asset('js/DataTable/Spanish.json') }}"
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'fullname', name: 'fullname'},
                {data: 'email', name: 'email'},
                {data: 'label', name: 'roles.label'},
                {data: 'created_at', name: 'created_at'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action',    name: 'action', searchable: false, orderable: false},
            ]
        });
    });
</script>
@endsection