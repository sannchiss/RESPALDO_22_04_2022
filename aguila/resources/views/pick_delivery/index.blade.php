@extends('layouts.offcanvas')
@section('style')
	<link href="{{ asset('css/pick_delivery.css') }}" rel="stylesheet">
	<style type="text/css">
		.popover-document {width: 300px; max-width: 300px; z-index: 1039;}
		.popover-document-body{max-height: 300px;overflow-x: scroll;}
		#mapRoute{height: 400px;}
		#mapDocument{height: 600px;}
	</style>
@endsection
@section('content')
    
	@include('helpers.breadcrumb')
	<div class="row">
		<div class="col-md">
			<div class="card">
				 <div class="card-body">
					<form>
						<div class="form-row">
							<div class="form-group col-md">
							    <label for="office_id">Oficinas</label>
							    <select id="office_id" name="office_id" class="form-control">
								    <option value="0" selected>Todas</option>
								    @foreach($offices as $office)
								    	<option value="{{ $office->id }}" {{ $office_id == $office->id ? 'selected':''}}>{{ $office->label }}</option>
								    @endforeach
							    </select>
							</div>
							<div class="form-group col-md">
							    <label for="vehicle_condition_id">Condicion del Vehiculos</label>
							    <select id="vehicle_condition_id" name='vehicle_condition_id' class="form-control">
								    <option value="0">Todas</option>
								    @foreach($vehicle_conditions as $vehicle_condition)
								    	<option value="{{ $vehicle_condition->id }}" {{ $vehicle_condition_id == $vehicle_condition->id ?'selected':''}}>{{ $vehicle_condition->label }}</option>
								    @endforeach
							    </select>
							</div>
							<div class="form-group col-md">
									<label for="route_status_id">Estado de Ruta</label>
									<select id="route_status_id" name='route_status_id' class="form-control">
										<option value="0">Todas</option>
										@foreach($statusRoutes as $statusRoute)
											<option value="{{ $statusRoute->id }}" {{ $route_status_id == $statusRoute->id ?'selected':''}}>{{ $statusRoute->label }}</option>
										@endforeach
									</select>
							</div>
							   
							<div class="form-group col-md" id="date_start_id">
								<label for="date_start" >Fecha inicio</label>
								<div class="input-group date" id="date_start_dv" data-target-input="nearest">
								<input class="form-control"  type="text" name="date_start" id="date_start" data-toggle="datetimepicker" data-target="#date_start"  >
								<div class="input-group-append" data-target="#date_start" data-toggle="datetimepicker">
										<div class="input-group-text"><i class="fa fa-calendar"></i></div>
									</div></div>
							</div>
							<div class="form-group col-md" id="date_end_id">
								<label for="date_end" >Fecha fin</label>
								<div class="input-group date" id="date_end_dv" data-target-input="nearest">
								<input class="form-control"  type="text"  name="date_end" id="date_end" data-toggle="datetimepicker" data-target="#date_end" >
								<div class="input-group-append" data-target="#date_end" data-toggle="datetimepicker">
										<div class="input-group-text"><i class="fa fa-calendar"></i></div>
									</div></div>
							</div>
						</div>
						<button type="submit" class="btn btn-primary">Filtar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<br>
	<!-- graficos -->
	<div class="row">
		<div class="col-md">
			<div class="card">
				<h5 class="card-header">Estado General</h5>
				 <div class="card-body">
				    <canvas id="status-general" width="250" height="160"></canvas>
				</div>
			</div>
		</div>

		<div class="col-md">
			<div class="card">
				<h5 class="card-header">Documentos por tipo de transporte</h5>
				 <div class="card-body">
				    <canvas id="status-by-carriers" width="250" height="160"></canvas>
				</div>
			</div>
		</div>
		<div class="col-md">
			<div class="card">
				<h5 class="card-header">Estado General</h5>
				 <div class="card-body">
				 	<h3 class="row">
				 		<div class="col-sm-5">
				 			<small class="text-muted">Planillas o Rutas</small>
				 		</div>
				 		<div class="col-sm-2">
				 			<span class="badge badge-info" id="total_routes">{{ $totalRoutes}}</span>
				 		</div>
				 	</h3>
				 	<h3 class="row">
				 		<div class="col-sm-5">
				 			<small class="text-muted">Guias o facturas</small>
				 		</div>
				 		<div class="col-sm-2">
				 			<span class="badge badge-info" id="total_documents">{{ $totalDocuments}}</span>
				 		</div>
				 	</h3>
				 	<h3 class="row">
				 		<div class="col-sm-5">
				 			<small class="text-muted">Pedidos u Ordenes</small>
				 		</div>
				 		<div class="col-sm-2">
				 			<span class="badge badge-info" id="total_orders">{{ $totalOrders}}</span>
				 		</div>
				 	</h3>
				 	<h3 class="row">
				 		<div class="col-sm-5">
				 			<small class="text-muted">Vehiculos en ruta</small>
				 		</div>
				 		<div class="col-sm-2">
				 			<span class="badge badge-info" id="total_vehicles">{{ $totalVehicles}}</span>
				 		</div>
				 	</h3>
				 	<h3 class="row">
				 		<div class="col-sm-5">
				 			<small class="text-muted">Clientes a visitar</small>
				 		</div>
				 		<div class="col-sm-2">
				 			<span class="badge badge-info" id="total_customers">{{ $totalCustomers}}</span>
				 		</div>
				 	</h3>
				</div>
			</div>
		</div>
	</div>
	<br>
	<!-- Vehiculos -->
	<div>
		<div class="row">
			@foreach($routes as $route)
				@include('pick_delivery.vehicle_card',['route' =>$route])
			@endforeach
		</div>
		<br>
	</div>


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


@endsection

<!-- OFFCANVAS -->
@section('offcanvas-content')
	@include('pick_delivery.documents')
@endsection
@section('script')

	<script src="{{ asset('js/document.js') }}"></script>
	<script src="{{ asset('js/map_document.js') }}"></script>
	<script type="text/javascript">

		var tableDocuments;
		var urlVehiclePosition = '{{ route('pick_delivery.vehicle_position',['']) }}';
		var urlCustomerPositions = '{{ route('pick_delivery.customer_positions') }}';
		var today = '{{ date('Y-m-d') }}';
		
		var currentParams = {
				'vehicle_condition_id': {{$vehicle_condition_id}}, 
				'route_status_id' : {{$route_status_id}},
	        	'office_id'	: {{$office_id}},
	        	'date_start': '{{$date_start}}', 
	        	'date_end':   '{{$date_end}}',
	        };

	    var currentUrl = '{{ route('pick_delivery.index') }}?'+ $.param( currentParams );

		$(document).ready(function(){
			$('.status-circle, .gouge-hide canvas').tooltip();
			getInfo();
			

		});

		function initMap(){
			initTwoMap();
		}

    	function getInfo() {
   			$.get(currentUrl, {}, function(res,resp) {
   				$('#total_documents').html(res.total_documents);
   				$('#total_vehicles').html(res.totalVehicles);
   				$('#total_routes').html(res.totalRoutes);
   				$('#total_orders').html(res.totalOrders);
   				$('#total_customers').html(res.totalCustomers);
   				//actualizacion de  charts
   				status_general.data.datasets[0].data = [res.totalInDelivery,res.totalAccepted,res.totalIncidence];//this update the value of may
				status_general.update();

				documentTypeTransport.data.datasets[0].data = [res.totalOwn, res.totalRented];
				documentTypeTransport.update();

   				//actualizacion vehiculos
   				$.each(res.routes,function(index, value){
   					$('#vehicle-'+value.vehicle_id+' canvas')
   						.attr('data-value',value.processed)
   						.attr('data-value','[{"from": 0, "to": '+value.accepted+', "color": "rgba(75, 192, 192, 1)"}]');

   					$('#vehicle-'+value.vehicle_id+' .first_time').html(value.first_time);
   					$('#vehicle-'+value.vehicle_id+' .last_time').html(value.last_time);

   					if(value.rejected > 0){
   						$('#vehicle-'+value.vehicle_id+' .rejected_circle').html(value.rejected).addClass('status-1');
   					}
   					if(value.partial_rejection > 0){
   						$('#vehicle-'+value.vehicle_id+' .partial_rejection_circle').html(value.partial_rejection).addClass('status-2');
   					}
   					if(value.redespaching > 0){
   						$('#vehicle-'+value.vehicle_id+' .redespaching_circle').html(value.redespaching).addClass('status-3');
   					}

   				})
   				
   			}, "json");
   			 window.setTimeout(getInfo,30000);
   		}

		//Habilitación de campos rango de fecha por defecto
		$('#date_start').datetimepicker({
			locale: 'es', 
			format:'YYYY-MM-DD HH:mm', 
			date: moment('{{$date_start}}'),
			buttons: {
				showToday: true,
				showClear: true,
				showClose: true
			}
		});

		$('#date_end').datetimepicker({
			locale: 'es',
			format:'YYYY-MM-DD HH:mm',
			date: moment('{{$date_end}}'),
			buttons: {
				showToday: true,
				showClear: true,
				showClose: true
			} 
		});

	// Deshabilita campos rango de fechas cuando estatus ruta: reparto
	if($('#route_status_id').val() == 3){ 
			
			$('#date_start').datetimepicker('disable');
			$('#date_start').datetimepicker({
				locale: 'es', 
				format:'YYYY-MM-DD HH:mm', 
				date: moment('{{$date_start}}'),
			buttons: {
				showToday: true,
				showClear: true,
				showClose: true
			}
			});
			$('#date_end').datetimepicker('disable');
			$('#date_end').datetimepicker({
				locale: 'es', 
				format:'YYYY-MM-DD HH:mm', 
				date: moment('{{$date_end}}'),
			buttons: {
				showToday: true,
				showClear: true,
				showClose: true
			}
			});

				}
			else{
				$('#date_start').datetimepicker('enable');
				$('#date_start').datetimepicker({
					locale: 'es', 
					format:'YYYY-MM-DD HH:mm', 
					date: moment('{{$date_start}}'),
			buttons: {
				showToday: true,
				showClear: true,
				showClose: true
			}
				});
				$('#date_end').datetimepicker('enable');
				$('#date_end').datetimepicker({
					locale: 'es', 
					format:'YYYY-MM-DD HH:mm', 
					date: moment('{{$date_end}}'),
			buttons: {
				showToday: true,
				showClear: true,
				showClose: true
			}
				});
				}

		$('#route_status_id').change(function(){  
				
			if($(this).val() == 3){ 
			
				$('#date_start').datetimepicker('disable');
				$('#date_start').datetimepicker({
					locale: 'es', 
					format:'YYYY-MM-DD HH:mm', 
					date: moment('{{$date_start}}'),
					buttons: {
						showToday: true,
						showClear: true,
						showClose: true
					}
				});
				$('#date_end').datetimepicker('disable');
				$('#date_end').datetimepicker({
					locale: 'es', 
					format:'YYYY-MM-DD HH:mm', 
					date: moment('{{$date_end}}'),
			buttons: {
				showToday: true,
				showClear: true,
				showClose: true
			}
				});
		
						}
					else{
				$('#date_start').datetimepicker('enable');
				$('#date_start').datetimepicker({
					locale: 'es', 
					format:'YYYY-MM-DD HH:mm', 
					date: moment('{{$date_start}}'),
			buttons: {
				showToday: true,
				showClear: true,
				showClose: true
			}
				});
				$('#date_end').datetimepicker('enable');
				$('#date_end').datetimepicker({
					locale: 'es', 
					format:'YYYY-MM-DD HH:mm', 
					date: moment('{{$date_end}}'),
			buttons: {
				showToday: true,
				showClear: true,
				showClose: true
			}
				});
					}

			});

		//documents table
	    function getDocumentsTable (element){
	    	$('#doc-general').html("<div class='lds-ripple' style='margin: 0 auto;'><div></div><div></div></div>");
	    	$('#doc-vehicle').html('--');

	        if(tableDocuments){
	            tableDocuments.destroy();
	        }
	        let params = {
	        	'vehicle_id': element.data('vehicle_id'), 
	        	'date_start': element.data('date_start'), 
				'date_end': element.data('date_end'),
				'route_status_id' : {{$route_status_id}},
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


		var status_general = new Chart($("#status-general"), {
		    type: 'bar',
		    data: {
		        labels: ["En ruta", "Aceptadas", "Con incidencias"],
		        datasets: [{
		            data: [{{ $totalInDelivery }}, {{$totalAccepted}}, {{$totalIncidence}}],
		            backgroundColor: [
		            	'rgba(255, 159, 64, 0.2)',
		                'rgba(75, 192, 192, 0.2)',
		                'rgba(255, 99, 132, 0.2)'
		            ],
		            borderColor: [
		                'rgba(255, 159, 64, 1)',
		                'rgba(75, 192, 192, 1)',
		                'rgba(255,99,132,1)'
		                
		            ],
		            borderWidth: 1
		        }]
		    },
		    options: {
		    	legend: {
		            display: false,
		        },
		        scales: {
		            yAxes: [{
		                ticks: {
		                    beginAtZero:true
		                }
		            }]
		        }
		    }
		});


		var documentTypeTransport = new Chart($("#status-by-carriers"), {
	    	type: 'doughnut',
		    data: {
			    datasets: [{
			        data: [{{$totalOwn}}, {{$totalRented}}],
			        "backgroundColor":["rgb(255, 99, 132)","rgb(54, 162, 235)"]
			    }],

			    // These labels appear in the legend and in the tooltips when hovering different arcs
			    labels: [
			        'Propios',
			        'Rentados',
			    ]
			},
		});
	</script>
	<script async defer
	    src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API','') }}&callback=initMap">
	</script>
@endsection