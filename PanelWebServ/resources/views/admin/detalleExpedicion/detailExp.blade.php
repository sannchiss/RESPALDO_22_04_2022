@extends('layouts.master')

@section('title')
Entrega | Sannchiss  
@endsection

@section('content')
@include('admin.detalleExpedicion.detailtable')
@include('admin.detalleExpedicion.modalDetalleExp.detalle')


@endsection

@section('scripts')

<script>


 $(document).ready(function(){

 let url = "{{ route('processing.detalleExp') }}";
 $('#document-table').DataTable({		
    processing: true,
	serverSide: true,
    ajax: url,
	type:'GET', 
    pageLength: 50,               
		columns: [
                    {data: 'NUMERO_ENVIO', name:'NUMERO_ENVIO'},	        // Número de Orden Transporte
					{data: 'REFERENCIA', name: 'REFERENCIA'},		        // Referencia
					{data: 'created_at', name: 'created_at'},			    // Fecha de Envio
					{data: 'REFERENCIA', name: 'REFERENCIA'},				// Estado del documento
                    {data: 'action',    name: 'action', searchable: true, orderable: false} // Impresion de Etiqueta
	            ]					
              
              });

  $(document).on('click','.mostrarRuta',function(){

    let params = {
                'Item_id' : $(this).data('id')                
                };


    let url = "{{ route('processing.muestraRuta') }}?"+ $.param(params);


    axios.get(url)
    .then(function(data){
        //console.log(data)
        let html = "";
        $.each(data, function(i , item) {
              console.log(item[0].respuestaDetalleExpediciones)
              if(item[0].respuestaDetalleExpediciones.codigo_error==3)
                  { 
                    alert("la expedición no ha sido confirmada"); 
                    //$('#staticBackdrop').hide(); 
                    }
                   else{

    //console.log(item[0].respuestaDetalleExpediciones.listaEventos)
                   //    $('#ModalEvent').modal('show');
                 /*  var exp = JSON.stringify(item.respuestaDetalleExpediciones.expeNumero)
                   console.log(exp)
                   $('.Exp').html("DETALLE DEL PEDIDO: "+exp);*/
                   $.each(item[0].respuestaDetalleExpediciones.listaEventos, function(i, item) {
                    console.log(item.fecha_evento);
                   html += '<tr>' +
                   '<td>' + item.fecha_evento + '</td>' +
                   '<td>' + item.delegacion + '</td>' +
                   '<td>' + item.descripcion + '</td>' +
                   '</tr>';
                   }); }

                    $('#dataResult').html(html);


                            });
                       



    })
    .catch((error) => {
        console.log(error)
    })

     });


});

</script>
@endsection