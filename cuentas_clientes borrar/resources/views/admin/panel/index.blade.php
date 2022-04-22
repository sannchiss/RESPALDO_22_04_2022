@extends('layouts.master')
@section('title')
Creación | Sannchiss  
@endsection

@section('content')


@include('admin.panel.list')


 @endsection     


 @section('scripts')

<script type="text/javascript">

<script src="{{ asset('js/Processing/menu_fixe.js') }}"></script>



$(document).ready(function(){

console.log("Lectura");
let url = "{{ route('processing.usuarios') }}";


  //Datos al datatable
  $('#usuarios-table').DataTable({		
    processing: true,
	serverSide: true,
    ajax: url,
	type:'GET', 
    pageLength: 50, 
                 
				columns: [
                {data: 'id', name:'usuarios.id'},	                //  id
                {data: 'company', name: 'empresas.name'},	//  company
				{data: 'name', name: 'usuarios.name'},		        //  nombre usuario
				{data: 'email', name: 'usuarios.email'},			    //  email
                {data: 'password', name: 'usuarios.password'},	    //  contraseña
                {data: 'modality', name: 'usuarios.modality'},
                {data: 'action',    name: 'action', searchable: true, orderable: true} // Gestion
	            ]					
              
              });




$(".addUser").click(function(event){
    event.preventDefault();
    let params = {
    'empresaSelect' : $('#empresaSelect').val()               
    };
    
    var urlEmpresas = "{{route('processing.empresas')}}";
    
    axios.get(urlEmpresas)
    .then(function (response) {
        // handle success   
      
        console.log(response.data.company)  
       

        $.each(response, function(i , item) {
            console.log(item.company)
            $.each(item.company,function(i , it){
            console.log(it.name)

            $("#empresaSelect").append('<option value='+it.id+'>'+it.name+'</option>');

            }); 


        });

        
         
       
    })
     .catch(function (error) {
     // handle error
      console.log(error);
     })
     .then(function () {
     // always executed
     
     
       });

   var urlEjecutivos = "{{route('processing.ejecutivos')}}";
    axios.get(urlEjecutivos)
    .then(function (response) {
        // handle success   
        $('#ModalCenter').modal(true);
        console.log(response.data.ejecutivo)  
       

        $.each(response, function(i , item) {
            console.log(item.ejecutivo)
            $.each(item.ejecutivo,function(i , it){
            console.log(it.name)

            $("#ejecutivoSelect").append('<option value='+it.id+'>'+it.name+'</option>');

            }); 


        });

        
       
         
       
    })
     .catch(function (error) {
     // handle error
      console.log(error);
     })
     .then(function () {
     // always executed
     $('#ModalCenter').modal('hide');

     $('#usuarios-table').DataTable().ajax.reload();
     

     
       });

       

});


$(".save").click(function(event){
    event.preventDefault();
    let params = $('#formUsuario').serialize();
    let urlSave = "{{route('save.agregarUsuario')}}";
    axios.post(urlSave,params)
	.then((response)=>{
        $('#usuarios-table').DataTable().ajax.reload();
        $('#ModalCenter').modal(false);
										
	})
});



$("#ModalCenter").on("hidden.bs.modal", function(){
    $("#empresaSelect").empty();
    $("#ejecutivoSelect").empty();

});










});

</script>

 @endsection