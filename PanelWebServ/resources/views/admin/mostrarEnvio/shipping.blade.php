@extends('layouts.master')

@section('title')
Entrega | Sannchiss  
@endsection

@section('content')
@include('admin.mostrarEnvio.sendtable')


@endsection

@section('scripts')


<script>


 $(document).ready(function(){
  //alert("Carga");
  console.log("Lectura");
  let url = "{{ route('processing.consultaenvio') }}";
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
          {data: 'NUMERO_ENVIO', name:'NUMERO_ENVIO'},	    // Número de Orden Transporte
					{data: 'REFERENCIA', name: 'REFERENCIA'},		      // Referencia
					{data: 'created_at', name: 'created_at'},			    // Fecha de Envio
					{data: 'REFERENCIA', name: 'REFERENCIA'},				  // Estado del documento
          {data: 'action',    name: 'action', searchable: true, orderable: false} // Impresion de Etiqueta
	            ]					
              
              });


              $(document).on('click', '.imprimirPDF', function () {

                var Item_id = $(this).data('id');


                let params = {
                'Item_id' : $(this).data('id')                
              };


                let url = "{{ route('processing.etiquetapdf') }}?"+ $.param(params);

                  axios.get(url)
                    .then(function (response) {
                      // handle success
                      console.log(response.data);
                      /*$('#ModalCenter').modal('show');
                      
                      $('#ModalCenter').on('hidden.bs.modal', function(e) {
                      e.preventDefault();
                      $(this).removeData('bs.modal')
                      alert("Se cierra");
                  });


                      jQuery('embed#frame').attr('src', "data:application/pdf;base64, " + encodeURI(response.data)+"#toolbar=0&navpanes=0&scrollbar=0");*/
                     
                    

                    let pdfWindow = window.open("");
                     pdfWindow.document.write("<embed width='100%' height='100%' src='data:application/pdf;base64, " + encodeURI(response.data)+"#toolbar=0&navpanes=0&scrollbar=0'>");               

                    })
                    .catch(function (error) {
                      // handle error
                      console.log(error);
                    })
                    .then(function () {
                      // always executed
                      $('#document-table').DataTable().ajax.reload()
                    });



              });

              $(document).on('click','.anularEnvio', function(){

                let params = {
                'Item_id' : $(this).data('id')                
              };

              console.log(params)
              let url = "{{ route('processing.anularenvio') }}?"+ $.param(params);

              axios.get(url)
              .then(function(response){
              alert("Se anuló la orden")  
              console.log(response)
              $('#document-table').DataTable().ajax.reload();


              })
              .catch( function(error){
                alert(error);
              })




              });



              
  });


</script>




 @endsection