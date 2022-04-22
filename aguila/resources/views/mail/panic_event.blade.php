<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<style type="text/css">
		@import url("https://fonts.googleapis.com/css?family=Lato:300,400,600");
		@media (max-width: 400px) {
		 body { font-size: 10px !important;}
		 .total, .total-text {font-size: 10px !important;}
		}
		body{font-family: lato;font-size: 14px;font-weight:lighter; color:#343638; }
		.open, .close {max-width: 900px;margin: auto;width: 100%;background: #dc3545;color: white;}
		.open {border-radius: 6px 6px 0px 0px;}
		.close { border-radius: 0px 0px 6px 6px;}
		.header, .items, .message {max-width: 800px; margin: auto; width: 90%;}
		.header {border: 1px solid #6c757d; border-radius: 6px; padding: 4px;}
		.header-second {color: #6c757d;margin-top: 2px;}
		.header-second td{width: 50%;}
		.center{text-align: center !important; }
		.items {border-collapse: collapse;}
		.items thead{ background: #212529;color: white; }
		.header th{text-align: left; padding: 2px 0;}
		.left {text-align: left;}
		.header th span{color:#6c757d;}
		.header th .hard{color:#343638;}
		.items-head {height: 40px;}
		.items tbody tr {height: 40px;}
		.items .odd{background-color: rgba(0, 0, 0, 0.05);}
		.total-text { text-align: right; padding-right: 10px; }
		.border .total, .border .total-text {border-style: double none none none;}
		.total, .total-text {font-size: 16px;}
	</style>
	<title></title>
</head>
<body>
	<table width="100%">
	<tr>
	  <th>
	  <!-- Logo -->
	  <img src='{{ asset("img/companies/1000.jpg") }}' height="90">	
	  </th>
	 </tr>
​
	 <tr>
	  <th>
	  <!-- open cotizacion -->
	  	<table class="open">
	  		<tr>
		  		<th>
		  			<h2>ALERTA PANICO ACTIVADO EN VEHICULO</h3>
		  		</th>
	 			</tr>
	  	</table>
	  
	  </th>
	 </tr>
​
	 <tr>
	  <th >
	  	<br>
	  	<!--tabla encabezado -->
	  	<table class="message">
		  	<tr>
				<th class="left">Estimado,</th>
			</tr>
	  	</table>
	  	<table class="message">
		  	<tr>
				<th class="left">
					<p>Se ha generado una alerta de panico en el movil <strong> {{$vehicle->code}} </strong> </p>
					<table class="">
                        <tr>
							<th>Código vehiculo:</th>
							<th>{{$vehicle->code}}</th>
                        </tr>
                        <tr>
                            <th>Patente vehiculo:</th>
                            <th>{{$vehicle->plate_number}}</th>
                        </tr>
                        <tr>
                            <th>Nombre conductor:</th>
                            <th>{{$vehicle->fullname ?? 'No asignado'}}</th>
                        </tr>
                        <tr>
                            <th>Phono gps:</th>
                            <th>{{$device->phone_number}}</th>
                        </tr>
						<tr>
							<th>Fecha hora:</th>
							<th>{{$data->datetime}}</th>
						</tr>
						<tr>
							<th>Velocidad del vehiculo:</th>
							<th>{{$data->speed}}</th>
						</tr>
						<tr>
							<th>Estado del motor:</th>
							<th>{{$ignition ? 'Encendido' : 'Apagado'}}</th>
                        </tr>
					</table>
				</th>
			</tr>
          </table>
            <table class="message">
                <tr>
                  <th class="left">
                      <p>Rutas cargadas:</p>
                      <ul>
                          @foreach($routes as $route)
                            <li>$route->code</li>
                          @endforeach
                      </ul>

                  </th>
              </tr>
            </table>

            <table class="message">
                    <tr>
                      <th class="left">
                          <p>Ubicación:</p>
                          <img src='https://maps.googleapis.com/maps/api/staticmap?center={{$data->latitude}},{{$data->longitude}}&zoom=13&size=600x600&maptype=roadmap&markers=color:red%7C{{$data->latitude}},{{$data->longitude}}&key={{env("MAP_API")}}'>
                          <a href="https://www.google.com/maps/search/?api=1&query={{$data->latitude}},{{$data->longitude}}">Ver en google maps</a>	
                      </th>
                  </tr>
                </table>


​
	  	<!--fin Encabezado -->
	  	<br>
	  </th>
	 </tr>
​
	 <tr>
	  <th>
	  <!-- open cotizacion -->
	  	<table class="close">
	  		<tr>
		  		<th>
		  			<h4>Esto es una alerta automática generada por el SISTEMA AGUILA a la fecha {{ date('Y-m-d H:i:s')}} </h4>
		  		</th>
	 			</tr>
	  	</table>
	  
	  </th>
	 </tr>
	</table>
</body>
</html>
