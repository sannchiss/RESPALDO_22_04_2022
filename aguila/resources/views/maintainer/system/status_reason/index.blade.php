@extends('layouts.app')

@section('content')
    @include('helpers.breadcrumb')
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h3 class="card-title">Rason de estado 
                @if(Permission::hasPermission('create'))
                    <a type="button" class="btn btn-secondary btn-sm float-right show-form" href="{{ route('maintainer.system.status_reason.create') }}">
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
                <th>Area</th>
                <th>Estado padre</th>
                <th>Nombre</th>
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
            ajax: "{{ route('maintainer.system.status_reason.index') }}",
            language: {
                "url": dtLanguage
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'area', name: 'system_areas.label'},
                {data: 'status', name: 'statuses.label'},
                {data: 'label', name: 'label'},
                {data: 'created_at', name: 'created_at'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action',    name: 'action', searchable: false, orderable: false},
            ]
        });
    });
    var selectUrl = "{{ route('maintainer.system.status.index') }}";
    $(document).on('change', '#system_area_id', function(){
        let url = selectUrl +'?selectId=' + $(this).val();
        axios.get(url)
        .then(function(response){
            $('#status_id').html(response.data);
        });        
    });
</script>
@endsection