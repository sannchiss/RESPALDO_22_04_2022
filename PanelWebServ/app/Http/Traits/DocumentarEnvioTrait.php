<?php

namespace App\Http\Traits;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Log;
use App\Models\DocumentarEnvio as DocumentarEnvio;


trait DocumentarEnvioTrait {

        public function insertRequest(Request $request, $numero_envio,$etiqueta,$procesarEtiqueta){
 
            $documentar_envio = new DocumentarEnvio;
            $documentar_envio->REFERENCIA = $request->input('REFERENCIA');
            $documentar_envio->NUMERO_ENVIO = $numero_envio;
            $documentar_envio->CODIGO_ADMISION = $request->input('CODIGO_ADMISION');
            $documentar_envio->NUMERO_BULTOS = $request->input('NUMERO_BULTOS');
            $documentar_envio->CLIENTE_REMITENTE = $request->input('CLIENTE_REMITENTE');
            $documentar_envio->CENTRO_REMITENTE = $request->input('CENTRO_REMITENTE');
            $documentar_envio->NIF_REMITENTE = $request->input('NIF_REMITENTE');
            $documentar_envio->NOMBRE_REMITENTE = $request->input('NOMBRE_REMITENTE');
            $documentar_envio->DIRECCION_REMITENTE = $request->input('DIRECCION_REMITENTE');
            $documentar_envio->PAIS_REMITENTE = 'CL';
            $documentar_envio->CODIGO_POSTAL_REMITENTE = $request->input('CODIGO_POSTAL_REMITENTE');
            $documentar_envio->POBLACION_REMITENTE = $request->input('POBLACION_REMITENTE');
            $documentar_envio->PERSONA_CONTACTO_REMITENTE = $request->input('PERSONA_CONTACTO_REMITENTE');
            $documentar_envio->TELEFONO_CONTACTO_REMITENTE = '999999999';
            $documentar_envio->EMAIL_REMITENTE = 'xxxxx@ALERCE-GROUP.COM';
            $documentar_envio->NIF_DESTINATARIO = $request->input('NIF_DESTINATARIO');
            $documentar_envio->NOMBRE_DESTINATARIO = $request->input('NOMBRE_DESTINATARIO');
            $documentar_envio->DIRECCION_DESTINATARIO = $request->input('DIRECCION_DESTINATARIO');
            $documentar_envio->PAIS_DESTINATARIO = $request->input('PAIS_DESTINATARIO');
            $documentar_envio->CODIGO_POSTAL_DESTINATARIO = $request->input('CODIGO_POSTAL_DESTINATARIO');
            $documentar_envio->POBLACION_DESTINATARIO = $request->input('POBLACION_DESTINATARIO');
            $documentar_envio->PERSONA_CONTACTO_DESTINATARIO = 'TEST CONTACTO';
            $documentar_envio->TELEFONO_CONTACTO_DESTINATARIO = $request->input('TELEFONO_CONTACTO_DESTINATARIO');
            $documentar_envio->EMAIL_DESTINATARIO = $request->input('EMAIL_DESTINATARIO');
            $documentar_envio->CODIGO_PRODUCTO_SERVICIO = '01';
            $documentar_envio->KILOS = $request->input('KILOS');
            $documentar_envio->VOLUMEN = $request->input('VOLUMEN');
            $documentar_envio->CLIENTE_REFERENCIA = $request->input('CLIENTE_REFERENCIA');
            $documentar_envio->IMPORTE_REEMBOLSO = '0';
            $documentar_envio->IMPORTE_VALOR_DECLARADO = '0';
            $documentar_envio->TIPO_PORTES = 'P';
            $documentar_envio->OBSERVACIONES1 = 'PRUEBA GRABACION';
            $documentar_envio->OBSERVACIONES2 = 'OBSERVACIÓN 2';
            $documentar_envio->TIPO_MERCANCIA = 'P';
            $documentar_envio->VALOR_MERCANCIA = '0';
            $documentar_envio->MERCANCIA_ESPECIAL = 'N';
            $documentar_envio->GRANDES_SUPERFICIES = 'N';
            $documentar_envio->GRANDES_SUPERFICIES = 'N';
            $documentar_envio->PLAZO_GARANTIZADO = 'N';
            $documentar_envio->LOCALIZADOR = '';	
            $documentar_envio->NUM_PALETS = '0';
            $documentar_envio->FECHA_ENTREGA_APLAZADA = '';
            $documentar_envio->ENTREGA_APLAZADA = 'N';
            $documentar_envio->TIPOS_DOCUMENTO = 'FACT';
            $documentar_envio->TIPOS_DOCUMENTO_REFERENCIA = 'FACT-123';
            $documentar_envio->GESTION_DEVOLUCION_CONFORME = 'N';
            $documentar_envio->ENVIO_CON_RECOGIDA = 'N';
            $documentar_envio->IMPRIMIR_ETIQUETA = 'S';
            $documentar_envio->ETIQUETA64 = $etiqueta;
            $documentar_envio->PROCESAR_ETIQUETADO = $procesarEtiqueta;
            $documentar_envio->UBICACION_ETIQUETA = "";
            $documentar_envio->ENVIO_DEFINITIVO = 'N';
            $documentar_envio->TIPO_FORMATO = 'PDF';
            $documentar_envio->save();


        }
    


}

