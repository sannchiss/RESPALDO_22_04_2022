@extends('layouts.master')

@section('title')
Entrega | Sannchiss  
@endsection

@section('content')
@include('admin.gestionEntrega.gestiontable')


@endsection

@section('scripts')


<script>


 $(document).ready(function(){
  //alert("Carga");
  console.log("Lectura");
  let url = "{{ route('management.index') }}";
 /* $.ajax({
            type: "GET",
            url: url,           
            success: function () {
                 console.log(data);
                $('#postRequestData').html(data);
             
           }

        });
 */
  //Datos al datatable
  
  $('#document-table').DataTable({		
    processing: true,
	serverSide: true,
    ajax: url,
		type:'GET', 
    pageLength: 50,               
				columns: [
                {data: 'NUMERO_ENVIO', name:'NUMERO_ENVIO'},	       // Número de Orden Transporte
				{data: 'REFERENCIA', name: 'REFERENCIA'},		       // Referencia
				{data: 'created_at', name: 'created_at'},			   // Fecha de Envio
				{data: 'REFERENCIA', name: 'REFERENCIA'},			   // Estado del documento
                {data: 'action',    name: 'action', searchable: true, orderable: true} // Gestion de Entrega
	            ]					
              
              });



   $('.sendDelivery').click(function(){

    var chk =  document.getElementsByName('order_check[]')
    var len = chk.length
    
    var contador = 0;
    for(i= 0 ; i<len ; i++)
    {
        if(chk[i].checked){
        ++contador;
        
        }else{
        
        }
    }

    console.log(contador);
    if(contador == 0){
       
        $.confirm({
			title: 'Aviso!',
			content: 'DEBE SELECCIONAR PARA GESTIONAR ENTREGA',
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
    
    
    }else{
    
    $.confirm({
			title: 'Aviso',
			content:'<center>ESTA DE ACUERDO EN GESTIONAR LA ENTREGA DE:'+' <span style=color:BLUE>'+contador+' </span>ENVIOS</center>',
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
									text: 'Gestionar', 
									action: function () {
										
                                        let url = "{{route('management.delivery')}}";
										let params = $('#entrega_recogedor').serialize();
										//$.alert('CAMBIO REALIZADO');

										axios.post(url,params)
										.then((response)=>{
                                        //window.location.reload();
                                        $('#document-table').DataTable().ajax.reload()
									})
           								 }
									}
                    }
				});

            }// fin del else






});





              
  });


</script>




 @endsection
