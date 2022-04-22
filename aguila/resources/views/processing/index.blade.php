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
						<div class="form-group col-md-3">
							    <label for="search_office">Oficinas</label>
							    <select id="search_office" name="search_office" class="form-control">
								<option value="0" selected>Todas</option>
								    @foreach($offices as $office)
									<option value="{{ $office->id }}">{{ $office->label }}</option>
								    @endforeach
							    </select>
							</div>
						<div class="form-group col-md-3" >
								<label for="search_code">Seleccione Patente:</label>
								<select id="search_code" name="search_code" class="form-control">
								<option value="" selected>Seleccione</option>
								<option value="">Seleccione Patente</option>  
								</select>
							</div>
										
					<div class="col" id="list_group" style="display:none">
							{{--<label for="list_group">Todos:</label>
							<div class="form-group col-md-3" >
								<input type="checkbox" name="" id="checkAll">	
							</div>--}}
							<span class="float-right">
								<div class="input-group">
									<select class="custom-select" id="inputgroupstatus" aria-label="">
									  <option value="19">Aceptado</option>
									  <option value="16">Rechazado Parcial</option>
									  <option value="13">Rechazado</option>
									  <option value="10">Redespachado</option>
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
</div><br><br>
@include('processing.view_document.document')

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="{{ asset('js/Processing/menu_fixe.js') }}"></script>

<script>

 $(document).ready(function(){
  	//Seleccion/deseleccion de Checkbok
	/*$('#checkAll').change(function(){

		$("input:checkbox").prop('checked', $(this).prop("checked"));

	});*/

	$('#list_group').show();

	let params = {
			'search_office' : $('#search_office').val()
		};

		let url = "{{ route('processing.post_select') }}?" + $.param(params);
		

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
					{data: 'office', name: 'offices.label'},					// Oficina
					{data: 'vehicle', name: 'vehicles.code'},					// Vehiculo
					{data: 'ruta', name: 'routes.code'},						// Ruta del documento
					{data: 'order_number', name: 'documents.order_number'},		// Número de Documento
					{data: 'code', name: 'documents.code'},						// Número de Orden
					{data: 'label', name: 'statuses.label'},					// Estatus del documento
                    {data: 'action',    name: 'action', searchable: true, orderable: false}
	            ]				
			});


		$('#search_office').change(function(){
		var office_id = $(this).val();
		let url = "{{ route('processing.post_select') }}?";
		
		//esta es la peticion get, la cual se divide en tres partes. ruta,variables y funcion
		$.get("{{ route('processing.patente') }}?office_id="+office_id, function(data){
			var patente_select = '<option value="0">Seleccione Patente</option>';

			for(var code in data)
				{
					patente_select+='<option value="'+data[code].id+'">'+data[code].code+'</option>';
				}

				 $('#search_code').html(patente_select);
		});

		let params = {
				'office_id': $(this).val()
			};

		tableDocuments.ajax.url( url + $.param(params) ).load();

	});


	 $('#search_code').change(function(){ 
		
		let params = {
			'vehicle_id' : $(this).val(),
			'office_id' : $('#search_office').val()
		};
		let url = "{{ route('processing.post_select') }}?" + $.param(params);

		tableDocuments.ajax.url( url + $.param(params) ).load();
	 });

	 $('.send').click(function(){
	
		var chk =  document.getElementsByName('doc_check[]')
		var len = chk.length
        var contador = 0;
		for(i= 0 ; i<len ; i++)
		{
			if(chk[i].checked){
            ++contador;
			}else{
            
			}
		}
		
		if(contador == 0)
		{
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

			if(cad_status==19){cad_status='ACEPTADO';}
			else
			if(cad_status==16){cad_status='RECHAZADO PARCIAL';}	
			else
				if(cad_status==13){cad_status='RECHAZADO';}
			else
				if(cad_status==10){cad_status='REDESPACHADO';}


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
										
										let url = "{{route('processing.checkdocument')}}";
										var status = $('#inputgroupstatus').val();
										let params = $('#form_document').serialize()+"&status_button="+status;
										let formcheck = $('#form_document').serialize();
										$.alert('CAMBIO REALIZADO');

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