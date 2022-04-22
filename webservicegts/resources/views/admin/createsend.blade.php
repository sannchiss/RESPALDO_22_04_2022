@extends('layouts.master')

@section('title')
Dashboard | Sannchiss  
@endsection

@section('content')

<div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="title">Creación de Envio000</h5>
              </div>


              <div class="card-body">
                <form id="CreatingSend">
                  <div class="row">
                    <div class="col-md-5 pr-1">
                      <div class="form-group">
                        <label>NÚMERO DE ENVIO (disabled)</label>
                        <input type="text" class="form-control" disabled="" placeholder="Company" value="disabled temp.">
                      </div>
                    </div>
                    <div class="col-md-3 px-1">
                      <div class="form-group">
                        <label>REFERENCIA</label>
                        <input type="text" class="form-control" placeholder="REFERENCIA" value="ASIGNACIÓN 01" name="REFERENCIA">
                      </div>
                    </div>
                    <div class="col-md-2 pl-1">
                      <div class="form-group">
                        <label for="numero_bultos">NUMERO DE BULTOS</label>
                        <input type="text" class="form-control" placeholder="NUMERO_BULTOS" value="1" name="NUMERO_BULTOS">
                      </div>
                    
                    </div>
                    <div class="col-md-2 pr-1">
                      <div class="form-group">
                        <label for="exampleInputEmail1">CODIGO DE ADMISIÓN</label>
                        <input type="text" class="form-control" placeholder="COD.ADMS" value="345" disabled="" name="CODIGO_ADMISION">
                      </div>

                    </div>
                   
                  </div>
                  <div class="row">
                    <div class="col-md-6 pr-1">
                      <div class="form-group">
                        <label>CLIENTE REMITENTE</label>
                        <input type="text" class="form-control" placeholder="N° Company"  disabled="" value="900003905" name="CLIENTE_REMITENTE">
                      </div>
                    </div>
                    <div class="col-md-2 pl-1">
                      <div class="form-group">
                        <label>CENTRO REMITENTE</label>
                        <input type="text" class="form-control" placeholder="CENTRO REMITENTE" value="01" name="CENTRO_REMITENTE">
                      </div>
                    </div>
                    <div class="col-md-2 pl-1">
                      <div class="form-group">
                        <label>NIF REMITENTE</label>
                        <input type="text" class="form-control" placeholder="NIF REMITENTE" value="" name="NIF_REMITENTE">
                      </div>
                    </div>

                  </div>
                  <div class="row">
                    <div class="col-md-5">
                      <div class="form-group">
                        <label>NOMBRE DEL REMITENTE</label>
                        <input type="text" class="form-control" placeholder="Home Address" value="TRANSLOGIC S.A." name="NOMBRE_REMITENTE">
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
                        <input type="text" class="form-control" placeholder="CODIGO POSTAL" value="9380000">
                      </div>
                    </div>
                    <div class="col-md-4 pl-1">
                      <div class="form-group">
                        <label>POBLACIÓN REMITENTE</label>
                        <input type="text" class="form-control" placeholder="POBLACIÓN REMITENTE" value="LAMPA">
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
                    </div>

                    <div class="row">
                         <div class="col-md-12">
                            <div class="form-group">
                                <button type="button" class="send btn btn-success">Crear</button>
                            </div>
                        </div>
                    </div>


                  <!--<div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>About Me</label>
                        <textarea rows="4" cols="80" class="form-control" placeholder="Here can be your description" value="Mike">Lamborghini Mercy, Your chick she so thirsty, I'm in that two seat Lambo.</textarea>
                      </div>
                    </div>
                  </div>-->
                </form>
              </div>


            </div>
          </div>
         
         <!-- <div class="col-md-4">
            <div class="card card-user">
              <div class="image">
                <img src="..../assets/img/bg5.jpg" alt="...">
              </div>
              <div class="card-body">
                <div class="author">
                  <a href="#">
                    <img class="avatar border-gray" src="...../assets/img/mike.jpg" alt="...">
                    <h5 class="title"> </h5>
                  </a>
                  <p class="description">
                    michael24
                  </p>
                </div>
                <p class="description text-center">
                  "Lamborghini Mercy <br>
                  Your chick she so thirsty <br>
                  I'm in that two seat Lambo"
                </p>
              </div>
              <hr>
              <div class="button-container">
                <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
                  <i class="fab fa-facebook-f"></i>
                </button>
                <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
                  <i class="fab fa-twitter"></i>
                </button>
                <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
                  <i class="fab fa-google-plus-g"></i>
                </button>
              </div>
            </div>
          </div>-->
        </div>


        <?php

/*$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://gtstntpre.alertran.net/gts/seam/resource/restv1/auth/documentarEnvio/json?url=https://gtstntpre.alertran.net",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{\n    \"DOCUMENTAR_ENVIOS\": {\n        \"DOCUMENTAR_ENVIO\": [\n            {\n                \"REFERENCIA\": \"ASIGNACIÓN 01\",\n                \"CODIGO_ADMISION\": \"345\",\n                \"NUMERO_BULTOS\": \"1\",\n                \"CLIENTE_REMITENTE\": \"900003905\",\n                \"CENTRO_REMITENTE\": \"01\",\n                \"NIF_REMITENTE\": \"\",\n                \"NOMBRE_REMITENTE\": \"TRANSLOGIC S.A.\",\n                \"DIRECCION_REMITENTE\": \"LA ESTERA 0575 , 0575 575\",\n                \"PAIS_REMITENTE\": \"CL\",\n                \"CODIGO_POSTAL_REMITENTE\": \"9380000\",\n                \"POBLACION_REMITENTE\": \"LAMPA\",\n                \"PERSONA_CONTACTO_REMITENTE\": \"SANNCHISS\",\n                \"TELEFONO_CONTACTO_REMITENTE\": \"997416303\",\n                \"EMAIL_REMITENTE\": \"xxxxx@ALERCE-GROUP.COM\",\n                \"NIF_DESTINATARIO\": \"96976500-4\",\n                \"NOMBRE_DESTINATARIO\": \"SANNCHISS REMITENTE\",\n                \"DIRECCION_DESTINATARIO\": \"PRUEBA\",\n                \"PAIS_DESTINATARIO\": \"CL\",\n                \"CODIGO_POSTAL_DESTINATARIO\": \"8320000\",\n                \"POBLACION_DESTINATARIO\": \"SANTIAGO\",\n                \"PERSONA_CONTACTO_DESTINATARIO\": \"TEST SANNCHISS\",\n                \"TELEFONO_CONTACTO_DESTINATARIO\": \"997416303\",\n                \"EMAIL_DESTINATARIO\": \"sannchiss@gmail.com\",\n                \"CODIGO_PRODUCTO_SERVICIO\": \"01\",\n                \"KILOS\": \"4\",\n                \"VOLUMEN\": \"1\",\n                \"CLIENTE_REFERENCIA\": \"1234\",\n                \"IMPORTE_REEMBOLSO\": 0,\n                \"IMPORTE_VALOR_DECLARADO\": \"33500\",\n                \"TIPO_PORTES\": \"P\",\n                \"OBSERVACIONES1\": \"PRUEBA GRABACION\",\n                \"OBSERVACIONES2\": \"OBS2\",\n                \"TIPO_MERCANCIA\": \"P\",\n                \"VALOR_MERCANCIA\": \"200000\",\n                \"MERCANCIA_ESPECIAL\": \"N\",\n                \"GRANDES_SUPERFICIES\": \"N\",\n                \"PLAZO_GARANTIZADO\": \"N\",\n                \"LOCALIZADOR\": \"\",\n                \"NUM_PALETS\": 0,\n                \"FECHA_ENTREGA_APLAZADA\": \"\",\n                \"ENTREGA_APLAZADA\": \"N\",\n                \"TIPOS_DOCUMENTO\": [\n                    {\n                        \"TIPO\": \"FACT\",\n                        \"REFERENCIA\": \"123\"\n                    },\n                    {\n                        \"TIPO\": \"GD\",\n                        \"REFERENCIA\": \"456\"\n                    }\n                ],\n                \"GESTION_DEVOLUCION_CONFORME\": \"N\",\n                \"ENVIO_CON_RECOGIDA\": \"N\",\n                \"IMPRIMIR_ETIQUETA\": \"N\",\n                \"ENVIO_DEFINITIVO\": \"N\",\n                \"TIPO_FORMATO\": \"EPL\"\n            }\n        ]\n    }\n}",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic U1BFUkVaOkhlYnJlb3M5",
    "Content-Type: application/json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

*/
?>

@endsection




@section('scripts')
<!--<script type="text/javascript" src="../js/eventos.js"></script>-->


<script type="text/javascript" >
   $(document).on('click', '.send', function () {
    console.log("Boton");
    let headers = new Headers();
    headers.append('Content-Type', 'application/json');
    headers.append('Accept', 'application/json');

    headers.append('Access-Control-Allow-Origin', 'http://localhost:8000');
    headers.append('Access-Control-Allow-Credentials', 'true');

    headers.append('GET', 'POST', 'OPTIONS');
    headers.append('Authorization', 'Basic U1BFUkVaOkhlYnJlb3M5');


    var settings = {
		"url": "https://gtstntpre.alertran.net/gts/seam/resource/restv1/auth/documentarEnvio/json?url=https://gtstntpre.alertran.net",
		"method": "POST",
		"timeout": 0,
		"headers": headers,
		"data": JSON.stringify({"DOCUMENTAR_ENVIOS":{"DOCUMENTAR_ENVIO":[{"REFERENCIA":"ASIGNACIÓN 01","CODIGO_ADMISION":"345","NUMERO_BULTOS":"1","CLIENTE_REMITENTE":"900003905","CENTRO_REMITENTE":"01","NIF_REMITENTE":"","NOMBRE_REMITENTE":"TRANSLOGIC S.A.","DIRECCION_REMITENTE":"LA ESTERA 0575 , 0575 575","PAIS_REMITENTE":"CL","CODIGO_POSTAL_REMITENTE":"9380000","POBLACION_REMITENTE":"LAMPA","PERSONA_CONTACTO_REMITENTE":"SANNCHISS","TELEFONO_CONTACTO_REMITENTE":"997416303","EMAIL_REMITENTE":"xxxxx@ALERCE-GROUP.COM","NIF_DESTINATARIO":"96976500-4","NOMBRE_DESTINATARIO":"SANNCHISS REMITENTE","DIRECCION_DESTINATARIO":"PRUEBA","PAIS_DESTINATARIO":"CL","CODIGO_POSTAL_DESTINATARIO":"8320000","POBLACION_DESTINATARIO":"SANTIAGO","PERSONA_CONTACTO_DESTINATARIO":"TEST SANNCHISS","TELEFONO_CONTACTO_DESTINATARIO":"997416303","EMAIL_DESTINATARIO":"sannchiss@gmail.com","CODIGO_PRODUCTO_SERVICIO":"01","KILOS":"4","VOLUMEN":"1","CLIENTE_REFERENCIA":"1234","IMPORTE_REEMBOLSO":0,"IMPORTE_VALOR_DECLARADO":"33500","TIPO_PORTES":"P","OBSERVACIONES1":"PRUEBA GRABACION","OBSERVACIONES2":"OBS2","TIPO_MERCANCIA":"P","VALOR_MERCANCIA":"200000","MERCANCIA_ESPECIAL":"N","GRANDES_SUPERFICIES":"N","PLAZO_GARANTIZADO":"N","LOCALIZADOR":"","NUM_PALETS":0,"FECHA_ENTREGA_APLAZADA":"","ENTREGA_APLAZADA":"N","TIPOS_DOCUMENTO":[{"TIPO":"FACT","REFERENCIA":"123"},{"TIPO":"GD","REFERENCIA":"456"}],"GESTION_DEVOLUCION_CONFORME":"N","ENVIO_CON_RECOGIDA":"N","IMPRIMIR_ETIQUETA":"N","ENVIO_DEFINITIVO":"N","TIPO_FORMATO":"EPL"}]}}),
};

$.ajax(settings).done(function (response) {
		console.log(response);
});



});   
        



</script>    


@endsection