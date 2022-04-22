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
		<div class="col-lg-12 mb-2">
			<div class="card">
				 <div class="card-body">
					<form>
						<div class="form-row">
							<div class="form-group mx-sm-3 mb-2">
								<label for="date_start" >Fecha inicio</label>
								<div class="input-group date" id="date_start_dv" data-target-input="nearest">
							    <input class="form-control"  type="text" name="date_start" id="date_start" data-toggle="datetimepicker" data-target="#date_start" >
								<div class="input-group-append" data-target="#date_start" data-toggle="datetimepicker">
										<div class="input-group-text"><i class="fa fa-calendar"></i></div>
										   </div></div>
							</div>
							<div class="form-group mx-sm-3 mb-2">
								<label for="date_end" >Fecha fin</label>
								<div class="input-group date" id="date_end_dv" data-target-input="nearest">
							    <input class="form-control"  type="text"  name="date_end" id="date_end" data-toggle="datetimepicker" data-target="#date_end">
								<div class="input-group-append" data-target="#date_end" data-toggle="datetimepicker">
										<div class="input-group-text"><i class="fa fa-calendar"></i></div>
									</div></div>
							</div>
							<div class="form-group mx-sm-3 mb-1">
								<br>
								<button type="submit" class="btn btn-primary mb-2">Filtar</button>
							</div>
						</div>
					</form>
				</div>
			</div>
        </div>
	</div>


@include('reports.vehicles.documents')

<!-- Modal document detail -->
<div id="document-detail" class="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width:80% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Detalle de Vehiculo <span class="badge badge-secondary" id="doc-detail">--</span> </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="document-detail-table" class="table table-striped dt-responsive nowrap" style="width:100%">
                            <thead class="bg-info text-white">
                                <tr>
                                    <th>Id</th>
                                    <th>Ruta</th>
                                    <th>Estatus</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                        </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

<script type="text/javascript">

$(document).ready(function() {

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
        
	var params = { 
					'date_start':'{{$date_start}}',
					'date_end': '{{$date_end}}'
				};

				//Datos al datatable
				var tableDocuments = $('#document-table').DataTable({
				processing: true,
				serverSide: true,
                ajax: "{{ route('reports.vehicles.document') }}?" + $.param(params),
                pageLength: 20,
	            language: {
	                "url": dtLanguage,
	            },
	            columns: [
					{data: 'id', name: 'vehicles.id'},						//  Id	
					{data: 'office', name: 'offices.label'},			    //  Nombre oficina  
                    {data: 'label', name: 'vehicles.label'},			    //  Código del vehiculo
                    {data: 'action', name: 'action', searchable: false, orderable: false,},

	            ]
			});	

        var tableDocumentDetail;
		$(document).on('click','.show-detail', function(e){
			e.preventDefault();
			if(tableDocumentDetail){
	            tableDocumentDetail.destroy();
	        }
	        $('#document-detail #doc-detail').html( $(this).data('document') );
	        let url = $(this).attr('href');

	        //Datos al datatable
	        tableDocumentDetail = $('#document-detail-table').DataTable({
	            processing: true,
	            serverSide: true,
	            ajax: url,
	            pageLength: 20,
	            language: {
	                "url": dtLanguage,
	            },
	            columns: [
	                {data: 'id', name: 'routes.id'},
	                {data: 'code', name: 'routes.code'},
	                {data: 'status', name: 'statuses.label'},
                    {data: 'created_at', name: 'routes.created_at'},
	            ]
	        });
			$('#document-detail').modal('show');
		});

    });
    
	</script>
@endsection