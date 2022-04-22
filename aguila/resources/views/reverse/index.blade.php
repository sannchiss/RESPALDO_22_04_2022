@extends('layouts.app')
@section('style')
<link href="{{ asset('css/checkBox.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('content')
<div class="menu barraNormal">
	<div class="card" id="menu barraNormal">
		<div class="card-body">
			<form id="search-form">
				<div class="form-row" >
                    <div class="form-group col-md-3" >
						<label for="search_code">Seleccione Patente:</label>
						<select id="search_code" name="search_code" class="form-control">
							<option value="1" >Seleccione</option>
							@foreach ($code as $item)
                        <option value="{{$item->id}}">{{$item->code}}</option>  
                            @endforeach
						</select>
					</div>
					<div class="form-group col-md-3" >
						<label for="search_route">Seleccione Ruta:</label>
						<select id="search_route" name="search_route" class="form-control">
						<option value="1" selected>Seleccione</option>
						<option value="">Seleccione Ruta</option>  
						</select>
					</div>
					<div class="col">
							<div class="form-group col-md-3" style="display:none" id="checkDiv" >
							<label for="checkAll">Todos:</label>
								<input type="checkbox" name="" value="selectAll" id="checkAll">	
							</div>
					</div>
					<div class="col">
							<span class="float-right">
									<div class="input-group">
										<select class="custom-select" id="inputgroupstatus" aria-label="" name="status" >
										  <option value="5">Zona de Anclaje</option>
										</select>
										<div class="input-group-append">
										  <button class="send btn btn-primary" type="button"  >Cargar</button>
										</div>
									</div>
							</span>
					</div>
				</div>	
			</form>
		</div>
	</div>
</div>
<br>
@include('reverse.view_document.document')

@endsection

@section('script')
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="{{ asset('js/Processing/menu_fixe.js') }}"></script>

<script>

$(document).ready(function(){
	$('#checkAll').change(function(){
		$("input:checkbox").prop('checked', $(this).prop("checked"));
	});

    let url = "{{ route('reverse.document') }}?codigo_id=";
	let params = {
			'codigo_id' : $('#search_code').val()
		};

		//Datos al datatable
		var tableDocuments = $('#document-table').DataTable({
				processing: true,
				serverSide: true,
                ajax: url ,
				type:'GET', 
                pageLength: 50,
	            language: {
	                "url": dtLanguage,
				},
	            columns: [
					{data: 'vehicle', name: 'vehicles.code'},					// Patente del Vehiculo
					{data: 'code', name: 'routes.code'},						// Ruta del documento
					{data: 'order', name: 'documents.order_number'},			// Número de Orden
					{data: 'doc_code', name: 'documents.code'},					// Número de Documento
					{data: 'label', name: 'statuses.label'},					// Número de Orden
                    {data: 'action',    name: 'action', searchable: true, orderable: false}
	            ]				
			});

	$('#search_code').change(function(){
        var code = $(this).val();
		//esta el la peticion get, la cual se divide en tres partes. ruta,variables y funcion
		$.get("{{ route('reverse.routes') }}?code="+code, function(data){
			var ruta_select = '<option value="1">Seleccione Ruta</option>'+'<option value="0">Todas</option>';

			for(var code in data)
				{
					 ruta_select+='<option value="'+data[code].id+'">'+data[code].code+'</option>';
				}

				 $('#search_route').html(ruta_select);
		});
		tableDocuments.ajax.url( url + $(this).val() ).load();
		});

		$('#search_route').change(function(){
			$('#checkDiv').show();		
			let url = "{{ route('reverse.reload') }}?";	
			let params = {
				'codigo_id' : $('#search_code').val(),
				'code_route': $(this).val()
			};
				tableDocuments.ajax.url( url + $.param(params) ).load();
		});
	
		$('.send').click(function(){
	
			var chk =  document.getElementsByName('doc_check[]')
			var len = chk.length
			var contador = 0;

			if($('#checkAll').is(':checked')){
				var selectCheck = $('#checkAll').val();	
				}else{
				var selectCheck = "";	
				}
				
			for(i= 0 ; i<len ; i++){
				if(chk[i].checked){
				++contador;
				}else{
				}
			}
	
		if(contador == 0){
			$.confirm({
			title: 'Aviso!',
			content: 'DEBE SELECCIONAR PARA CAMBIAR EL ESTATUS',
			type: 'red',
			typeAnimated: true,
			buttons: {
				tryAgain: {
					text: 'CERRAR',
					btnClass: 'btn-red',
					action: function(){
					}
				}
			}
			});
		}
		else{
			var cad_status = $('#inputgroupstatus').val();

			if(cad_status==5){cad_status='Zona de Anclaje';}
		
			$.confirm({
				title: 'Aviso',
				content:'<center>ESTA DE ACUERDO EN CAMBIAR EL ESTATUS EN:'+' <span style=color:BLUE>'+cad_status+'</span></center>',
				type: 'green',
				boxWidth: '40%',
				useBootstrap: false,
				buttons: {
						cancel: function () {
								//close
							},
						cancel:{
								btnClass: 'btn-red any-other-class',
							},
							Confirmar: {
										btnClass: 'btn-green any-other-class',
										text: 'Cambiar', 
										action: function () {
											let patente_id = $('#search_code').val();
											let route = $('#search_route').val();
											let url = "{{route('reverse.checkdocument')}}";
											var status = $('#inputgroupstatus').val();
											let params = $('#form_document').serialize()+"&status_button="+status+"&patente_id="+patente_id+"&selectCheck="+selectCheck+"&route="+route;
											let formcheck = $('#form_document').serialize();
											$.alert('CAMBIO REALIZADO');
											console.log("Id Patente ".patente_id);
											console.log("Id Ruta ".route);
											console.log("Estatus ".status);


											axios.post(url,params)
											.then((response)=>{
											window.location.reload();
										})
												}
										}
						}
					});	

	 	} //FIN DEL ELSE

 });
		});

</script>
@endsection