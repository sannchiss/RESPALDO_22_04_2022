@extends('layouts.offcanvas')
<style type="text/css">
    #map {height: 600px;width: 100%;}
    #mapRoute{height: 400px;}
    #mapDocument{height: 600px;}
</style>
@section('content')
    <div class="container-fluid">
    @include('helpers.breadcrumb')

        <div class="row">
        <div class="col-md">
            <div class="card">
                 <div class="card-body">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="office_id">Oficinas</label>
                                <select id="office_id" name="office_id" class="form-control">
                                    <option value="0" selected>Todas</option>
                                    @foreach($offices as $office)
                                        <option value="{{ $office->id }}" {{ $office_id == $office->id ? 'selected':''}}>{{ $office->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="type">Tipo</label>
                                <select id="type" name="type" class="form-control">
                                    <option value="0" {{ $type === '0' ? 'selected':''}}>Todos</option>
                                    <option value="gps" {{ $type === 'gps' ? 'selected':''}}>GPS </option>
                                    <option value="cell" {{ $type === 'cell' ? 'selected':''}}>Telefono </option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="vehicleCondition_id">Condición del Vehiculo</label>
                                <select id="vehicleCondition_id" name="vehicleCondition_id" class="form-control">
                                    <option value="0" selected>Todas</option>
                                    @foreach ($vehicleConditions as $vehicleCondition)
                                    <option value="{{ $vehicleCondition->id }}" {{ $vehicleCondition_id == $vehicleCondition->id ? 'selected':''}}>{{ $vehicleCondition->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-1">
                                <label for="">&nbsp;</label>
                                <button  type="submit" class="btn btn-primary form-control">Filtar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div id="map"></div>

    <div class="card py-2">
        <div class="card-header bg-danger text-white">
            <h3 class="card-title">Dispositivos en monitoreo</h3>
            
            <div class="clearfix"></div>
        </div>
        <div class="card-body">
            <table id="index-table" class="table table-striped">
            <thead class="thead-dark">
              <tr>
                <th>Tipo</th>
                <th>Dispositivo</th>
                <th>Empleado</th>
                <th>vehiculo</th>
                <th>Condición Vehiculo</th>
                <th>Estado motor</th>
                <th>Velocidad</th>
                <th>Ultima Actualizacion</th>
                <th></th>
              </tr>
            </thead>
        </table>
        </div>
    </div>
    @include('helpers.modal',['id' => 'modal-form','class'=>'modal-lg'])
    @include('helpers.modalDelete')

    <!-- Modal map -->
    <div id="document-modal-map" class="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="document-modal-map-content">
                        <div id="mapDocument"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('offcanvas-content')
  @include('pick_delivery.documents')
@endsection
@section('script')
<script src="{{ asset('js/document.js') }}"></script>
<script type="text/javascript">
    var urlVehiclePosition = '{{ route('pick_delivery.vehicle_position',['']) }}';
    var urlCustomerPositions = '{{ route('pick_delivery.customer_positions') }}';
    var tableDocuments;
    var table;
    var office_id   = '{{$office_id}}';
    var device_type = '{{$type}}';
    var condition = '{{$vehicleCondition_id}}';

    $(document).ready(function() {

        table = $('#index-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('monitoring.index') }}?office_id={{$office_id}}&type={{$type}}&vehicleCondition_id={{$vehicleCondition_id}}",
            language: {
                "url": "{{ asset('js/DataTables/Spanish.json') }}"
            },
            columns: [
                {data: 'type', name: 'type'},
                {data: 'code', name: 'code'},
                {data: 'fullname', name: 'fullname'},
                {data: 'label', name: 'label'},
                {data: 'vehicle_conditions', name: 'vehicle_conditions'},
                {data: 'ignition_status', name: 'ignition_status'},
                {data: 'speed', name: 'speed'},
                {data: 'date_time', name: 'date_time'},
                {data: 'action',   name: 'action', searchable: false, orderable: false},
            ]
        });

        setInterval(function() {
            table.ajax.reload();
        }, 300000 );
    });

    //ini canvas map
    function initMap(){
        console.log('iniciando two mapas')
        initMonitoringMap();
        initTwoMap();
    }


    //documents table
        function getDocumentsTable (element){
            console.log(element.data('vehicle_id'));
            console.log(element.val());
            $('#doc-general').html("<div class='lds-ripple' style='margin: 0 auto;'><div></div><div></div></div>");
            $('#doc-vehicle').html('--');

            if(tableDocuments){
                tableDocuments.destroy();
            }
            let params = {
                'vehicle_id': element.data('vehicle_id'), 
                'date_start': moment().format('YYYY-MM-DD 00:00'), 
                'date_end': moment().format('YYYY-MM-DD 23:59')
            }; 


            //llena datos generales
            axios.get("{{ route('pick_delivery.vehicle') }}?" + $.param( params ))
            .then(function(response){
                $('#doc-vehicle').html(response.data.vehicle);
                $('#doc-general').html(response.data.general);
            });

            //Datos al datatable
            tableDocuments = $('#document-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('pick_delivery.documents') }}?" + $.param( params ),
                language: {
                    "url": dtLanguage,
                },
                columns: [
                    {data: 'action',    name: 'action', searchable: false, orderable: false},
                    {data: 'route', name: 'routes.code'},
                    {data: 'order', name: 'documents.order_number'},
                    {data: 'document', name: 'documents.code'},
                    {data: 'status', name: 'statuses.label'},
                    {data: 'customer', name: 'customer_branches.label'},
                    {data: 'processed_date', name: 'documents.processed_date'},
                    {data: 'received_by', name: 'documents.received_by'},
                    {data: 'observation', name: 'documents.observation'}
                ]
            });
            urlCustomerPositions = "{{ route('pick_delivery.customer_positions') }}?" + $.param( params );
            getRouteClientsMarkers(urlCustomerPositions, element.data('vehicle_id'));
        }
</script>
<script src="{{ asset('js/map.js') }}"></script>
<script src="{{ asset('js/map_document.js') }}"></script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API','') }}&callback=initMap">
</script>
@endsection