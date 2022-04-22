@extends('layouts.master')
@section('title')
Creación | Sannchiss  
@endsection

@section('content')

@include('admin.add.ejecutivo.form')


 @endsection     

 @section('scripts')
 <script src="{{ asset('js/accionesjs.js') }}"></script>


 <script type="text/javascript">
        

 $(document).ready(function(){
        
  let url = "{{ route('executive.listexecutive') }}";
  //Datos al datatable
  $('#ejecutivos-table').DataTable({		
    processing: true,
	serverSide: true,
    ajax: url,
	type:'GET', 
    pageLength: 50, 
                 
				columns: [
                {data: 'id', name:'id'},	                //  id
                {data: 'name', name: 'name'},	            //  nombre ejecutivo
				{data: 'phone', name: 'phone'},		        //  telefono ejecutivo
				{data: 'email', name: 'email'},			    //  email
                {data: 'action',    name: 'action', searchable: true, orderable: true} 
	            ]					
              
              });




$('.addexecutive').click(function(){

    let url = "{{ route('executive.addexecutive') }}";
    let params = $('#ejecutivoAgregar').serialize();
    console.log(params);
    axios.post(url,params)
	.then((response)=>{
										
	})

    .catch(function (error) {
     // handle error
      console.log(error);
     })
     .then(function () {
     // always executed

     

     
       });





});


});

</script>

@endsection