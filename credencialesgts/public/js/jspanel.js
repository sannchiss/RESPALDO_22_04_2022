$(document).ready(function(){

 /**BOTON AGREGAR USUARIO */
$(".addUser").click(function(){
   // id = $(this).attr('id')
   var urlEjecutivos = "processing/ejecutivos";
   console.log("Backend cuentas")
    axios.get(urlEjecutivos)
    .then(function (response) {
        // handle success   
        $('#ModalCenter').modal(true);
        console.log(response.data)  

        $.each(response.data, function(i , item) {
            console.log(item.name)
            $("#ejecutivoList").append('<option value='+item.id+'>'+item.name+'</option>');

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

/**SELECT BUSCAR EMPRESA */
var urlEmpresas = 'processing/usuarioEmpresas';
    axios.get(urlEmpresas)
    .then(function (response) {
        // handle success   

        $.each(response.data, function(i , item) {
        //   console.log(item.empresa)

            $("#listaCompany").append('<option value="'+item.empresa+'">'+item.empresa+'</option>');

        });

            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
            .then(function () {
            // always executed
     
            });

/****************************************************/

/* CAMPO AUTOCOMPLETE DE CUENTA EMPRESA AGREGAR USUARIO */
var pathEmpresa = 'processing/empresas';
    $('input.typeaheadEmpresa').typeahead({
        items: 80, //Cantidad de elementos mostrados en lista
        source:  function (query, process) {
         $.get(pathEmpresa, { query: query }, function (data) {
             console.table(data);
          objects = [];
          labelEmpresa = {};
          //Cliclo para vaciar el autocomplete
          $.each(data, function(i , item) {
          ///console.log(item.empresa)
          var queryLabel = item.empresa + ' - TXA: ' + item.cuenta_txa + ' - GTS: ' + item.cuenta_gts;
          var nameCompany = item.empresa;
          //console.log(nameCompany);
          labelEmpresa[queryLabel] = item;
          objects.push(queryLabel);
      });  

      process(objects); 
            }, 'json')
        },

      updater: function(queryLabel) {
          var item = labelEmpresa[queryLabel];
          var input_label = queryLabel;
          $('#hiddeEmpresaid').val(item.empresa);
          $('#txa').val(item.cuenta_txa);
          $('#gts').val(item.cuenta_gts);
          return input_label;
         
      }

    });

/**Acciones de Tabla de usuarios */
/**AGREGAR CUENTA(S) A USUARIOS CREADOS */
$(document).on('click','.addAcount',function(event){
       event.preventDefault();  
        $('#addMoldalCredencial').modal(true);
        Item_idUser = $(this).data('id');
    });
$(document).on('click','.listAcount',function(event){
       event.preventDefault();  
        $('#modalListEmpresa').modal(true);

        let params = {
            'Item_id' : $(this).data('id')
        };

        var urlUserAcountList = "processing/listaCuentas?"+ $.param(params);

        //Datos al datatable
        var cuentas_usuarios = $('#cuentas-usuario-table').DataTable({		
            processing: true,
            destroy: true,
            serverSide: true,
            ajax: urlUserAcountList,
            type:'GET', 
            pageLength: 50, 
                        
                        columns: [
                        {data: 'empresa', name: 'cuentas_usuarios.empresa'},	        //  empresa
                        {data: 'cuenta_txa', name: 'cuentas_usuarios.cuenta_txa'},		//  cuenta_txa
                        {data: 'cuenta_gts', name: 'cuentas_usuarios.cuenta_gts'},	    //  cuenta_gts
                        {data: 'action',    name: 'action', searchable: true, orderable: true} // Gestion
                        ]					
                    
                    });  

        /**Eliminar cuenta asociada usuario*/
        $(document).on('click','.delete',function(event){
            event.preventDefault(); 
            let params = {
                'Item_id' : $(this).data('id')                
              };
                    let url = "processing/eliminarCuenta?"+ $.param(params);;
                    axios.delete(url)
                    .then((response)=>{
                       // console.log(response)
                        $('#cuentas-usuario-table').DataTable().ajax.reload();
                    })

                });        

    });

/**Editar Info */
$(document).on('click','.editInfo',function(){
     
    $('#ShowCredencial').modal(true);
    Item_id = $(this).data('id');

    //Autocomplete comercial ventana editar usuario
    var pathComercial = 'processing/ejecutivos';
    $('input.typeaheadComercial').typeahead({
        source:  function (query, process) {
         $.get(pathComercial, { query: query }, function (data) {
            objects = [];
            labelComercial = {};
            //Cliclo para vaciar el autocomplete
            $.each(data, function(i , item) {
                console.log(item.name)
                var queryLabel = item.name;
                labelComercial[queryLabel] = item;
                objects.push(queryLabel);
            }); 
            process(objects); 
            }, 'json')
        },

        updater: function(queryLabel) {
            var item = labelComercial[queryLabel];
            var input_label = queryLabel;
            $('#hiddeComercialId').val(item.id);
            return input_label;
        }

    });


    let params = {
        'Item_id' : $(this).data('id')                
      };
    var url = "processing/detalleUsuario?"+ $.param(params);
    
    axios.get(url)
    .then(function (response) {

 $.each(response, function(i , item) {
 //   console.log(item.response)
    var flag = 0;
    $.each(item.credencialDetalle,function(i , it){
  //  console.log(it.name)
    $("#hiddeUsuarioId").val(it.id);
    $("#name").val(it.name);
    $("#user").val(it.user);
    $("#email").val(it.email);
    $("#password").val(it.password);
    if(it.modality == "B2B")
    {flag = 1; }else{flag = 2;}
    $("#modalidad").val(flag);
    $("#hiddeComercialId").val(it.comercial_id);
    $("#comercial").val(it.comercial);

    }); 


        });


    });


    });

    
//Boton que Guarda la edicion de datos del usuario
    $(".editUsuario").click(function(event){
        let params = $('#cambiarCuenta').serialize();
         let urlEdit = "processing/editarUsuario";
          axios.post(urlEdit,params)
          .then((response)=>{
            $('#ShowCredencial').modal('hide');

        })
  });




$(".saveAcountUser").click(function(event){
    event.preventDefault();
    let params = $('#addCuentaUsuario').serialize()+"&idUser="+Item_idUser;
    let urlSaveAcount = 'save/agregarCuentaUsuario';
    axios.post(urlSaveAcount,params)
    .then(function (response) {
        console.log(response.data);
        $('#usuarios-table').DataTable().ajax.reload();
        $('#addMoldalCredencial').modal('hide');
                                        
    })
});



$(".save").click(function(event){
    event.preventDefault();
    let params = $('#formUsuario').serialize();
    //let urlSave = "{{route('save.agregarUsuario')}}";
    let urlSave = 'save/agregarUsuario';
    axios.post(urlSave,params)
	.then((response)=>{
        $('#usuarios-table').DataTable().ajax.reload();
        $('#ModalCenter').modal('hide');
										
	})
});


$("#ModalCenter").on("hidden.bs.modal", function(){
    $("#formUsuario")[0].reset();
    $("#addCuentaUsuario")[0].reset();
    $("#ejecutivoList").empty();
});

$("#addMoldalCredencial").on("hidden.bs.modal", function(){
    $("#addCuentaUsuario")[0].reset();
    $("#formUsuario")[0].reset();
});
        
$("#modalListEmpresa").on("hidden.bs.modal", function(){
 //  $("#cuentas-usuario-table").DataTable().fnDestroy();
});

$("#ShowCredencial").on("hidden.bs.modal", function(){
    $('#usuarios-table').DataTable().ajax.reload();
   });


});