@extends('layouts.app')
@section('style')
	<style type="text/css">
		.popover-document {width: 300px; max-width: 300px; z-index: 1039;}
		.popover-document-body{max-height: 300px;overflow-x: scroll;}
		#mapRoute{height: 400px;}
		#mapDocument{height: 600px;}
	</style>
@endsection
@section('content')
    
	<div class="row">
		<div class="col-md-15">
			<div class="card">
				 <div class="card-body">
					<form>
						<div class="form-row">
							<div class="form-group mx-sm-3 mb-2">
							    <label for="date_start" >Fecha inicio</label>
							    <input class="form-control"  type="text" name="date_start" id="date_start" data-toggle="datetimepicker" data-target="#date_start" >
							</div>
							<div class="form-group mx-sm-3 mb-2">
							    <label for="date_end" >Fecha fin</label>
							    <input class="form-control"  type="text"  name="date_end" id="date_end" data-toggle="datetimepicker" data-target="#date_end">
							</div>
							<div class="form-group mx-sm-3 mb-1">
								<br>
								<button type="submit" class="btn btn-primary mb-2">Filtar</button>
							</div>
							
							<div class="form-group mx-sm-3 mb-1">
									<br>
									<a class="btn btn-success " href="{{ route('reports.export',[$date_start,$date_end]) }}" download>Exportar Excel<i class="fas fa-file-excel"></i></a>
								</div>	

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<br>

@include('reports.documents')

<!-- Modal document detail -->
<div id="document-detail" class="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width:90% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Detalle de documento <span class="badge badge-secondary" id="doc-detail">--</span> </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="document-detail-table" class="table table-striped dt-responsive nowrap" style="width:100%">
                            <thead class="bg-info text-white">
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Cantidad</th>
                                    <th>Cantidad aceptada</th>
                                    <th>Cantidad rechazada</th>
                                    <th>Estado</th>
                                    <th>Razon rechazo</th>
                                </tr>
                            </thead>
                        </table>
                </div>
            </div>
        </div>
    </div>

<!-- Modal document image -->
<div id="document-modal-image" class="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<div class="document-modal-image-content">
					<div class='lds-ripple' style='margin: 0 auto;'><div></div><div></div></div>
				</div>
			</div>
		</div>
	</div>
</div>


@endsection

@section('script')
<script src="{{ asset('js/document.js') }}"></script>

<script type="text/javascript">

$(document).ready(function() {

	$('#date_start').datetimepicker({
			locale: 'es', 
			format:'YYYY-MM-DD HH:mm', 
			date: moment('{{$date_start}}')
		});

		$('#date_end').datetimepicker({
			locale: 'es',
			format:'YYYY-MM-DD HH:mm',
			date: moment('{{$date_end}}') 
		});



	var params = { 
					'date_start':'{{$date_start}}',
					'date_end': '{{$date_end}}'
				};

				//Datos al datatable
				var tableDocuments = $('#document-table').DataTable({
				processing: true,
				serverSide: true,
                ajax: "{{ route('reports.document') }}?" + $.param(params),
                pageLength: 20,
	            language: {
	                "url": dtLanguage,
	            },
	            columns: [
					{data: 'route', name: 'routes.code'},						// Ruta del documento
                    {data: 'order', name: 'documents.order_number'},			// Número de Orden
                    {data: 'document', name: 'documents.code'},					// Número de Documento
                    {data: 'status', name: 'statuses.label'},					// Estatus del Documento
					{data: 'customer', name: 'customer_branches.label'},		// Nombre del Cliente
					{data: 'rut', name: 'customer_branches.rut',autoFill: true},// Rut del Cliente 
					{data: 'name_seller',name: 'name_seller'},	                // Nombre Completo Vendedor
					{data: 'rut_seller',name: 'employees.rut'},	                // Rut Vendedor
					{data: 'name_driver',name: 'name_driver'},	                // Nombre Completo Conductor
					{data: 'rut_driver',name: 'employees_driver.rut'},	        // Rut Conductor
					{data: 'patent', name:'vehicles.code'},						// Patente del Vehiculo
					{data: 'packages', name:'documents.packages'},			    // Cantidad de Bultos
                    {data: 'created_at', name: 'documents.created_at'}, 		// Fecha de Creación del Documento
                    {data: 'processed_date', name: 'documents.processed_date'}, // Fecha de Gestión del Documento
                    {data: 'action',    name: 'action', searchable: false, orderable: false,},

	            ]
			});	new $.fn.dataTable.FixedHeader( tableDocuments );


    });
    
	</script>
@endsection