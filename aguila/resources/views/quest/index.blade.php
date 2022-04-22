@extends('layouts.app')
@section('style')
<link href="{{ asset('js/select2/css/select2.min.css') }}" rel="stylesheet">
	<style type="text/css">
		  #mapDocument{
		      height: 600px;
		    }
		.popover-document {
			width: 300px;
			max-width: 300px;
			z-index: 1039;
		    
		}
		.popover-document-body{
			max-height: 300px;
		    overflow-x: scroll;
		}
		.select2-container .select2-selection--single {
			height: calc(2.19rem + 2px) !important;
    		padding: 0.375rem 0.75rem;
    	}

	</style>
@endsection
@section('content')
	<div class="card">
			<div class="card-body">
			<form id="search-form">
				<div class="form-row">
					<div class="form-group col-md-3">
						<label for="search_type">Tipo Busqueda</label>
						<select id="search_type" name="search_type" class="form-control">
							<option value="" selected>Seleccione</option>
							<option value="ROUTE">Por Planilla ruta</option>
							<option value="DOCUMENT">Por Guia o Factura</option>
							<option value="ORDER">Por Pedido</option>
							<option value="CUSTOMER">Por Cliente</option>
						</select>
					</div>
					<div style="display:none" class="form-row col-md-9" id="customer_search">
						<div class="form-group col-md-4 customer_id-input">
							<label for="customer_id" >Cliente</label>
							<select class="form-control"  style="width: 100%," data-placeholder="Seleccione un cliente" type="text" name="customer_id" id="customer_id"></select>
							<span class="invalid-feedback">
								<strong></strong>
							</span>
						</div>
						<div class="form-group col-md-3 date_start-input">
							<label for="date_start" >Fecha inicio</label>
							<input class="form-control"  type="text" name="date_start" id="date_start" data-toggle="datetimepicker" data-target="#date_start" >
							<span class="invalid-feedback">
								<strong></strong>
							</span>
						</div>
						<div class="form-group col-md-3 date_end-input">
							<label for="date_end" >Fecha fin</label>
							<input class="form-control"  type="text"  name="date_end" id="date_end" data-toggle="datetimepicker" data-target="#date_end" >
							<span class="invalid-feedback">
								<strong></strong>
							</span>
						</div>
						<div class="form-group col-md-2">
							<label for="search_button">&nbsp;</label>
							<button type="submit" id="search_button" class="search_button form-control btn btn-info">Buscar</button>
						</div>
					</div>
					<div style="display:none" class="form-row col-md-9 searching-input" id="all_search">
						<div class="form-group col-md-10">
							<label for="searching" id="dinamic_label"></label>
							<input class="form-control"  type="text" name="searching" id="searching">
							<span class="invalid-feedback">
								<strong></strong>
							</span>
						</div>
						<div class="form-group col-md-2">
							<label for="search_button">&nbsp;</label>
							<button type="submit" id="search_button" class="search_button form-control btn btn-info">Buscar</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<br>
<!-- Resultado de busquedad -->
    <div id="search_result" class="card" style="display: none">
        <div class="card-header bg-danger text-white">
            <h3 class="card-title">Resultados</h3>
        </div>
        <div class="card-body">
            <table id="document-table" class="table table-striped dt-responsive" style="width:100%">
				<thead class="thead-dark">
					<tr>
						<th></th>
						<th>Vehiculo</th>
						<th>Ruta</th>
						<th>Pedido</th>
						<th>Guía o Factura</th>
						<th>Estado</th>
						<th>Cliente</th>
						<th>Fecha entrega</th>
						<th>Recibido</th>
						<th>Observacion</th>
					</tr>
				</thead>
			</table>
        </div>
    </div>
	<!-- Modal document detail -->
	<div id="document-detail" class="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" style="max-width:70% !important;">
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

@section('script')
<script src="{{ asset('js/jquery-ui.js') }}"></script>
<script src="{{ asset('js/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('js/select2/js/i18n/es.js') }}"></script>
<script src="{{ asset('js/document.js') }}"></script>
<script src="{{ asset('js/map_document.js') }}"></script>
<script type="text/javascript">
	var urlVehiclePosition = '{{ route('quest.vehicle_position',['']) }}';
	$('#date_start').datetimepicker({
		locale: 'es', 
		format:'YYYY-MM-DD', 
		date: moment('{{ $date_start }}')
	});
	$('#date_end').datetimepicker({
		locale: 'es',
		format:'YYYY-MM-DD',
		date: moment('{{ $date_end }}') 
	});

	function initMap(){
    	initOneMap();
  	}

	$(document).on('change','#search_type',function(e){
		let selection = $(this).val();
		let name = '' 

		$('#search_result').hide('slide', {direction: 'up'});
		//borra errores
		$('#search-form .has-error').each(function(){
	    	$(this).find('.invalid-feedback strong').html('');
	    	$(this).find('input,select,.special-invalid').removeClass('is-invalid');
	    	$(this).removeClass('has-error');
		});

		if(selection == ''){
			$('#all_search').hide('slide', { direction: 'left' });
			$('#customer_search').hide('slide', { direction: 'left' });
			return;
		}
		if(selection == 'CUSTOMER'){
			$('#all_search').hide('slide', { direction: 'left' }, function() {
				$('#customer_search').show('slide', { direction: 'left' });
  			});

		} else{
			//aplico nombre del campo de busquedad
			switch(selection) {
			    case 'ROUTE':
			        name = 'No. Planilla Ruta';
			        break;
			    case 'ORDER':
			        name = 'No. Pedido';
			        break;
			    case 'DOCUMENT':
			        name = 'No. Guía o factura'
			        break;
			    default:
			        name = ''
			        break;
			} 
			$('#dinamic_label').html(name);

			$('#customer_search').hide('slide', { direction: 'left' },function() {
				$('#all_search').show('slide', { direction: 'left' });
			});
			
		}
	});
	var tableDocuments;
	$(document).on('click', '.search_button', function(e){
		e.preventDefault();
		$('#search_result').hide('slide', {direction: 'up'});
		let inputs = $('#search-form').serialize();
		let url = "{{ route('quest.index') }}?"+inputs;
		if(tableDocuments){
	        tableDocuments.destroy();
	    }
		 //Datos al datatable
	    tableDocuments = $('#document-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: { 
	        	"url"  : url,
	        	"error": function(jqXHR, textStatus, errorThrown){
					let errors = jqXHR.responseJSON.errors
					$.each( errors , function( key, value ) {
		        		$('.'+key+'-input').addClass('has-error');
		        		$('.'+key+'-input input, .'+key+'-input select, .'+key+'-input .special-invalid').addClass('is-invalid');
		        		$('.'+key+'-input .invalid-feedback strong').html(value[0]); //showing only the first error.
		    		});
				},
				"dataSrc": function ( json ) {
					$('#search_result').show('slide', {direction: 'up'});
			      	return json.data;
				}
			},
	        pageLength: 50,
	        language: {
	            "url": dtLanguage,
	        },
	        columns: [
	         	{data: 'action',    name: 'action', searchable: false, orderable: false},
	         	{data: 'vehicle', name: 'vehicles.label'},
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

	});

var selectOption = {
language: "es",
  ajax: {
    url: "{{ route('quest.customers') }}",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        q: params.term, // search term
      };
    },
    processResults: function (data) {
      return {
        results: data
      };
    },
  },
  minimumInputLength: 2
}

$('#customer_id').select2(selectOption);
</script>
<script async defer
  src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API','') }}&callback=initMap">
</script>
@endsection


