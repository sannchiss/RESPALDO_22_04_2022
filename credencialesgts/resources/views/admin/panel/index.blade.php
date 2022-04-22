@extends('layouts.master')
@section('title')
Creación | Sannchiss  
@endsection

@section('content')


@include('admin.panel.list')
@include('admin.panel.modal.addAcount')
@include('admin.panel.modal.listCompany')
@include('admin.panel.modal.showCredencial')


 @endsection        


 @section('scripts')
<script src="{{ asset('js/jspanel.js') }}"></script>

<script type="text/javascript">

$(document).ready(function(){

        //Datos al datatable
        var tableDocuments =  $('#usuarios-table').DataTable({		
            processing: true,
            serverSide: true,
            dom: "Bfrtip",
            buttons: [{
            extend: "pdf",
            title: "Customized PDF Title",
            filename: "customized_pdf_file_name"
            }, {
            extend: "excel",
            title: "Customized EXCEL Title",
            filename: "customized_excel_file_name"
            }, {
            extend: "csv",
            filename: "customized_csv_file_name"
            }],
            buttons: ['csv', 'excel', 'print'],
            ajax: "{{ route('processing.usuarios') }}",
            type:'GET', 
            pageLength: 25,
                                   
                        columns: [
                        {data: 'id', name:'usuarios.id'},	                //  id
                        {data: 'name', name: 'usuarios.name'},		        //  nombre usuario
                        {data: 'user', name: 'usuarios.user'},		        //  usuario
                        {data: 'password', name: 'usuarios.password'},	    //  contraseña
                        {data: 'email', name: 'usuarios.email'},	        //  email
                        {data: 'modality', name: 'usuarios.modality'},
                        {data: 'action',    name: 'action', searchable: true, orderable: true} // Gestion
                        ]					
                    
                    });

           $('#listaCompany').change(function(){ 
            let params = {
             'empresa' : $(this).val()
                          };
             //console.log(params.empresa);          
                let url = "{{ route('query.consultaEmpresa') }}?" + $.param(params);
                          tableDocuments.ajax.url( url ).load();
                      });




});



</script>

 @endsection