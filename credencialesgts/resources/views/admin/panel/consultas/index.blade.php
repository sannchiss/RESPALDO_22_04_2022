@extends('layouts.master')
@section('title')
Creación | Sannchiss  
@endsection

@section('content')


@include('admin.panel.consultas.listCuentas')
@include('admin.panel.modal.showCredencial')

 @endsection     


 @section('scripts')

 <script type="text/javascript">
        

  $(document).ready(function(){

    var urlEmpresas = "{{route('processing.usuarioEmpresas')}}";
    axios.get(urlEmpresas)
    .then(function (response) {
        // handle success   
      
        console.log(response.data)
        $.each(response.data, function(i , item) {
            console.log(item.empresa)

            $("#empresaSelect").append('<option value='+item.id+'>'+item.company+'</option>');

        });


            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
            .then(function () {
            // always executed
     
     
            });


  
            let params = { 
            'ind' : 'conUser'
        };

        let urlStand = "{{ route('processing.credenciales') }}?" + $.param(params);
        var tableDocuments =  $('#usuarios-table').DataTable({		
                processing: true,
                serverSide: true,
                ajax: urlStand,
                type:'GET', 
                pageLength: 50, 
                    
                    columns: [
                    {data: 'id', name:'usuarios.id'},	                //  id
                    {data: 'company', name: 'company.empresa'},	//  company
                    {data: 'name', name: 'usuarios.name'},		        //  nombre usuario
                    {data: 'email', name: 'usuarios.email'},			    //  email
                    {data: 'password', name: 'usuarios.password'},	    //  contraseña
                    {data: 'modality', name: 'usuarios.modality'},
                    {data: 'action',    name: 'action', searchable: true, orderable: true} // Gestion
                    ]					
              
              });





        $('#empresaSelect').change(function(){ 
            
                let params = {
                    'id' : $(this).val()
                };
             
                console.log(params.id)
                
                let url = "{{ route('query.consultaEmpresa') }}?" + $.param(params);


                tableDocuments.ajax.url( url ).load();
	        });

            $(document).on('click','.mostrarCredencial',function(){
         
            $('#ShowCredencial').modal(true);
            Item_id = $(this).data('id');

            let params = {
                'Item_id' : $(this).data('id')                
              };

            var url = "{{route('processing.detalleUsuario')}}?"+ $.param(params);
            
            axios.get(url)
            .then(function (response) {

         $.each(response, function(i , item) {
         //   console.log(item.response)
            var flag = 0;
            $.each(item.credencialDetalle,function(i , it){
          //  console.log(it.name)
            $("#name").val(it.name);
            $("#user").val(it.user);
            $("#email").val(it.email);
            $("#password").val(it.password);
            $("#empresa").val(it.company);
            if(it.modality == "B2B")
            {flag = 1; }else{flag = 2;}
            $("#modalidad").val(flag);
            $("#cuentatxa").val(it.acountTXA);
            $("#cuentagts").val(it.acountGTS);
            $("#comercial").val(it.comercial);

            }); 

            


        });

        


            });

            
        var urlEjecutivos = "{{route('processing.ejecutivos')}}";
        axios.get(urlEjecutivos)
        .then(function (response) {
        // handle success   
        console.log(response.data.ejecutivo)  
       

        $.each(response, function(i , item) {
            console.log("Ejecutivos modal");
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

     //$('#usuarios-table').DataTable().ajax.reload();
     

     
       });
         

            

            });
        
         
            $(document).on('click','.addAcount',function(){
            });


            $(".editUsuario").click(function(event){
            alert(Item_id);

             let params = $('#cambiarCuenta').serialize()+"&id="+Item_id;
             let urlEdit = "{{route('processing.editarUsuario')}}";
              axios.post(urlEdit,params)
              .then((response)=>{
            
                          
        })
      });

      var pathEmpresa = "{{ route('processing.empresas') }}";
      $('input.typeaheadEmpresa').typeahead({
          source:  function (query, process) {
          return $.get(pathEmpresa, { query: query }, function (data) {
                  return process(data);
              });
          }
      });


      var pathComercial = "{{ route('processing.ejecutivos') }}";
      $('input.typeaheadComercial').typeahead({
          source:  function (query, process) {
          return $.get(pathComercial, { query: query }, function (data) {
                  return process(data);
              });
          }
      });


            $("#ShowCredencial").on("hidden.bs.modal", function(){
            $("#ejecutivoSelect").empty();
            

});  




        });

</script>


 @endsection