@extends('layouts.master')

@section('title')
Creación | Sannchiss  
@endsection

@section('content')

@include('admin.crearEnvio.resultado')


<div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="title">Datos Remitente</h5>
              </div>

             <!-- <select id="regiones"></select>
              <select id="comunas"></select>-->

              <div class="card-body">
                <form id="CreatingSend">
                {{ csrf_field() }}
                  <div class="row">
                    <div class="col-md-3 pr-1">
                      <div class="form-group">
                        <label>NÚMERO DE ENVIO (disabled)</label>
                        <input type="text" class="form-control" placeholder="N° de Envio" value=" " name="NUMERO_ENVIO">
                      </div>
                    </div>
                    <div class="col-md-3 px-1">
                      <div class="form-group">
                        <label>REFERENCIA</label>
                        <input type="text" class="form-control" placeholder="REFERENCIA /NUMERO DE SEGUIMIENTO" value="12345" name="REFERENCIA">
                      </div>
                    </div>
                    <div class="col-md-3 pr-1">
                      <div class="form-group">
                        <label for="numero_bultos">NUMERO DE BULTOS</label>
                        <input type="text" class="form-control" placeholder="NUMERO_BULTOS" name="NUMERO_BULTOS" value= "2">
                      </div>
                    
                    </div>
                    <div class="col-md-3 pr-1">
                      <div class="form-group">
                        <label for="exampleInputEmail1">CODIGO DE ADMISIÓN</label>
                        <input type="text" class="form-control" placeholder="COD.ADMS" value="345" name="CODIGO_ADMISION" >
                      </div>

                    </div>
                   
                  </div>
                  <div class="row">
                    <div class="col-md-6 pr-1">
                      <div class="form-group">
                        <label>CLIENTE REMITENTE</label>
                        <input type="text" class="form-control" placeholder="N° Company"   value="900007424" name="CLIENTE_REMITENTE" >
                      </div>
                    </div>
                    <div class="col-md-3 pr-1">
                      <div class="form-group">
                        <label>CENTRO REMITENTE</label>
                        <input type="text" class="form-control" placeholder="CENTRO REMITENTE" value="01" name="CENTRO_REMITENTE">
                      </div>
                    </div>
                    <div class="col-md-2 pl-1">
                      <div class="form-group">
                        <label>NIF REMITENTE</label>
                        <input type="text" class="form-control" placeholder="NIF REMITENTE" value="1-9" name="NIF_REMITENTE">
                      </div>
                    </div>

                  </div>
                  <div class="row">
                    <div class="col-md-5">
                      <div class="form-group">
                        <label>NOMBRE DEL REMITENTE</label>
                        <input type="text" class="form-control" placeholder="Home Address" value="TESTFNLNCHILE" name="NOMBRE_REMITENTE">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>DIRECCIÓN DEL REMITENTE</label>
                        <input type="text" class="form-control" placeholder="direccion" value="LA ESTERA 0575 , 0575 575" name="DIRECCION_REMITENTE">
                      </div>
                    </div>

                  </div>


                  <div class="row">
                    <div class="col-md-4 pr-1">
                      <div class="form-group">
                        <label>PAIS</label>
                        <input type="text" class="form-control" placeholder="pais" value="CL" name="PAIS_REMITENTE">
                      </div>
                    </div>
                    <div class="col-md-4 px-1">
                      <div class="form-group">
                        <label>CÓDIGO POSTAL</label>
                        <input type="text" class="form-control" placeholder="CODIGO POSTAL" value="9250000" name="CODIGO_POSTAL_REMITENTE">
                      </div>
                    </div>
                    <div class="col-md-4 pl-1">
                      <div class="form-group">
                        <label>POBLACIÓN REMITENTE</label>
                        <input type="text" class="form-control" placeholder="POBLACIÓN REMITENTE" value="MAIPU" name="POBLACION_REMITENTE">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                  <div class="col-md-4 pr-1">
                      <div class="form-group">
                        <label>PERSONA CONTACTO</label>
                        <input type="text" class="form-control" placeholder="PERSONA CONTACTO" value="SANNCHISS" name="PERSONA_CONTACTO_REMITENTE">
                      </div>
                    </div>
                    <div class="col-md-4 pr-1">
                      <div class="form-group">
                        <label>PERSONA CONTACTO</label>
                        <input type="text" class="form-control" placeholder="PERSONA CONTACTO" value="SANNCHISS" name="PERSONA_CONTACTO_REMITENTE">
                      </div>
                    </div>


                    </div>
                    <div class="col-xs-12"><hr></div>

                    <div class="row">
                    <div class="col-lg-12">
                          <h5 class="title">Datos Destinatario</h5>
                    </div>
                    <div class="card-body">
                    <div class="row">
                    <div class="col-md-4 pr-1">
                      <div class="form-group">
                        <label>CLIENTE REFERENCIA</label>
                        <input type="text" class="form-control" placeholder="CLIENTE REFERENCIA" value="CORRELATIVO-0000" name="CLIENTE_REFERENCIA" >
                      </div>                      
                    </div>
                    <div class="col-md-4 pr-1">
                      <div class="form-group">
                        <label>NIF DESTINATARIO</label>
                        <input type="text" class="form-control" placeholder="NIF DESTINATARIO" value="1-9" name="NIF_DESTINATARIO" >
                      </div>                      
                    </div>
                    <div class="col-md-4 pr-1">
                      <div class="form-group">
                        <label>NOMBRE DESTINATARIO</label>
                        <input type="text" class="form-control" placeholder="NOMBRE DESTINATARIO" value="TEST-DESTINATARIO" name="NOMBRE_DESTINATARIO" >
                      </div>                      
                    </div>
                    <div class="col-md-4 pr-1">
                      <div class="form-group">
                        <label>DIRECCION DESTINATARIO</label>
                        <input type="text" class="form-control" placeholder="DIRECCION DESTINATARIO" value="SANTA CATALINA DE CHENA, 1001" name="DIRECCION_DESTINATARIO" >
                      </div>                      
                    </div>
                    <div class="col-md-4 pr-1">
                      <div class="form-group">
                        <label>PAIS DESTINATARIO</label>
                        <input type="text" class="form-control" placeholder="PAIS DESTINATARIO" value="CL" name="PAIS_DESTINATARIO" >
                      </div>                      
                    </div>
                    <div class="col-md-4 pr-1">
                      <div class="form-group">
                        <label>CODIGO POSTAL DESTINATARIO</label>
                        <input type="text" class="form-control" placeholder="CÓDIGO POSTAL DESTINATARIO" value="8320000" name="CODIGO_POSTAL_DESTINATARIO" >
                      </div>                      
                    </div>
                    <div class="col-md-4 pr-1">
                      <div class="form-group">
                        <label>POBLACIÓN DESTINATARIO</label>
                        <input type="text" class="form-control" placeholder="POBLACIÓN DESTINATARIO" value="SANTIAGO" name="POBLACION_DESTINATARIO" >
                      </div>                      
                    </div>
                    <div class="col-md-4 pr-1">
                      <div class="form-group">
                        <label>PERSONA DESTINATARIO</label>
                        <input type="text" class="form-control" placeholder="PERSONA DESTINATARIO" value="SANTIAGO" name="PERSONA_CONTACTO_DESTINATARIO" >
                      </div>                      
                    </div>
                    <div class="col-md-4 pr-1">
                      <div class="form-group">
                        <label>TLF CONTAC. DESTINATARIO</label>
                        <input type="text" class="form-control" placeholder="PERSONA DESTINATARIO" value="999999999" name="TELEFONO_CONTACTO_DESTINATARIO" >
                      </div>                      
                    </div>
                    <div class="col-md-4 pr-1">
                      <div class="form-group">
                        <label>EMAIL DESTINATARIO</label>
                        <input type="text" class="form-control" placeholder="EMAIL DESTINATARIO" value="none@none.com" name="EMAIL_DESTINATARIO" >
                      </div>                      
                    </div>
                    <div class="col-md-2 pr-1">
                      <div class="form-group">
                        <label>KILOS</label>
                        <input type="text" class="form-control" placeholder="KILOS" value="2" name="KILOS" >
                      </div>                      
                    </div>
                    <div class="col-md-2 pr-1">
                      <div class="form-group">
                        <label>VOLUMEN</label>
                        <input type="text" class="form-control" placeholder="VOLUMEN" value="0" name="VOLUMEN" >
                      </div>                      
                    </div>
                    
                    </div>
                    </div>
                    </div>
                    </div>
                        </div>


                    <div class="row">
                         <div class="col-md-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-success" class="btn btn-primary" onclick="CrearServicio()">Enviar Solicitud</button>
                            </div>
                        </div>
                    </div>


                  
                </form>
              </div>


            </div>
          </div>
         
     
        </div>

@endsection




@section('scripts')
<script src="{{ asset('js/selectDependienteChile.js') }}"></script>


<script type="text/javascript">

function CrearServicio() {  	
      let inputs = $("#CreatingSend").serialize();
      let url = "{{ route('processing.crearenvio') }}?"+inputs;
      console.log(inputs);


        $.ajax({
            type:'POST', 
            url: url,
            success:function(data){
                //
              let html = "";
              $.each(JSON.parse(data), function(i, item) {
             // alert(item.respuestaDocuemtarEnvio.resultado);
                
              //alert(data);  
              var notify = $.notify('<strong>Saving</strong> No Cierre la página...', {
              type: 'success',
              allow_dismiss: false,
              showProgressbar: false
            });

            setTimeout(function() {
              console.log(item.respuestaDocuemtarEnvio.resultado)
              html += '<tr>' +
                   '<td> Resultado: ' + item.respuestaDocuemtarEnvio.resultado + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td> Código: ' + item.respuestaDocuemtarEnvio.codigo_admision + '</td>' +
                    '</tr>' + 
                    '<tr>' +
                    '<td> N°: ' + item.respuestaDocuemtarEnvio.numero_envio + '</td>' +
                    '</tr>';
                   
            $('#dataResult').html(html);
            $('#staticBackdrop').modal('show');
              notify.update('message', '<strong>Guardando</strong> Resultado: '+item.respuestaDocuemtarEnvio.resultado);
            }, 1000);

            
            /*
            setTimeout(function() {
              notify.update('message', '<strong>Código</strong> Admisión: '+item.respuestaDocuemtarEnvio.codigo_admision);
              
            }, 2000);
           
            setTimeout(function() {
              notify.update('message', '<strong>Número de Envio</strong>: '+item.respuestaDocuemtarEnvio.numero_envio);
            }, 3000);

            setTimeout(function() {
              notify.update('message', '<strong>Envío Creado</strong>');
            }, 4000);

          */

          });
          
              


           },
           error:function(data){
            //lo que devuelve si falla tu archivo mifuncion.php
            alert("Error en Envio");
                        

           }
        
    });
  }
</script>    


@endsection