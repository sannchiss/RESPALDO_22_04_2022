
    $(document).on('click', '.send', function (e) {
        e.preventDefault();

   
        var dataString = $('#CreatingSend').serialize();
		let url = "{{url('CrearEnvio')}}?";
       
        var jsonData=$('#CreatingSend').serializeArray()
       .reduce(function(a, z) { a[z.name] = z.value; return a; }, {});
       console.log(jsonData.REFERENCIA);
       
       
        //console.log('Datos serializados: '+dataString);



        /*$.ajax({
            type: "POST",
            url: url,
            data: dataString,
            success: function(data) {
                alert("Se ha realizado el POST con exito "+msg);

            },
            error: function(jqXHR, text, error){
                toastr.error('Validation error!', 'No se pudo Añadir los datos<br>' + error, {timeOut: 5000});
            }
        });*/


      



        var settings = {
            "url": "https://gtstntpre.alertran.net/gts/seam/resource/restv1/auth/documentarEnvio/json?url=https://gtstntpre.alertran.net",
            "method": "POST",
            "timeout": 0,
            "headers": {
                    "Authorization": "Basic U1BFUkVaOkhlYnJlb3M5",
                    "Content-Type": "application/json"
            },
            "data": JSON.stringify({"DOCUMENTAR_ENVIOS":{"DOCUMENTAR_ENVIO":[{"REFERENCIA":jsonData.REFERENCIA,"CODIGO_ADMISION":"345","NUMERO_BULTOS":"1","CLIENTE_REMITENTE":"900003905","CENTRO_REMITENTE":"01","NIF_REMITENTE":"","NOMBRE_REMITENTE":"TRANSLOGIC S.A.","DIRECCION_REMITENTE":"LA ESTERA 0575 , 0575 575","PAIS_REMITENTE":"CL","CODIGO_POSTAL_REMITENTE":"9380000","POBLACION_REMITENTE":"LAMPA","PERSONA_CONTACTO_REMITENTE":"SANNCHISS","TELEFONO_CONTACTO_REMITENTE":"997416303","EMAIL_REMITENTE":"xxxxx@ALERCE-GROUP.COM","NIF_DESTINATARIO":"96976500-4","NOMBRE_DESTINATARIO":"SANNCHISS REMITENTE","DIRECCION_DESTINATARIO":"PRUEBA","PAIS_DESTINATARIO":"CL","CODIGO_POSTAL_DESTINATARIO":"8320000","POBLACION_DESTINATARIO":"SANTIAGO","PERSONA_CONTACTO_DESTINATARIO":"TEST SANNCHISS","TELEFONO_CONTACTO_DESTINATARIO":"997416303","EMAIL_DESTINATARIO":"sannchiss@gmail.com","CODIGO_PRODUCTO_SERVICIO":"01","KILOS":"4","VOLUMEN":"1","CLIENTE_REFERENCIA":"1234","IMPORTE_REEMBOLSO":0,"IMPORTE_VALOR_DECLARADO":"33500","TIPO_PORTES":"P","OBSERVACIONES1":"PRUEBA GRABACION","OBSERVACIONES2":"OBS2","TIPO_MERCANCIA":"P","VALOR_MERCANCIA":"200000","MERCANCIA_ESPECIAL":"N","GRANDES_SUPERFICIES":"N","PLAZO_GARANTIZADO":"N","LOCALIZADOR":"","NUM_PALETS":0,"FECHA_ENTREGA_APLAZADA":"","ENTREGA_APLAZADA":"N","TIPOS_DOCUMENTO":[{"TIPO":"FACT","REFERENCIA":"123"},{"TIPO":"GD","REFERENCIA":"456"}],"GESTION_DEVOLUCION_CONFORME":"N","ENVIO_CON_RECOGIDA":"N","IMPRIMIR_ETIQUETA":"N","ENVIO_DEFINITIVO":"N","TIPO_FORMATO":"EPL"}]}}),
    };
    
    $.ajax(settings).done(function (response) {
            console.log(response);
    });





    
    
    });