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
	  <img src="{{ asset("img/companies/{$info->office_code}.jpg") }}" height="90">	
	  </th>
	 </tr>
​
	 <tr>
	  <th>
	  <!-- open cotizacion -->
	  	<table class="open">
	  		<tr>
		  		<th>
		  			<h2> Notificación de despacho  </h3>
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
					<th class="left">Estimado vendedor, {{$info->sales_name}} </th>
			</tr>
	  	</table>
	  	<table class="message">
		  	<tr>
				<th class="left">Por medio del presente le notificamos que la(s) siguientes faturas de su cliente <strong>{{$info->customer_name}}</strong>, seran despachada(s) el día de hoy <strong> {{date('Y-m-d')}} </strong> en el domicilio registrado <strong>{{$info->customer_address}}</strong> </th>
			</tr>
	  	</table>
​
	  	<!--fin Encabezado -->
	  	<br>
	  	<!--Items -->
	  	@foreach($documents as $details)
	  		<h4>Documento: {{ $details[0]->document_code }} </h4>
		  	<table class="items">
		  		<thead>
				  	<tr class="items-head">
							<th>CODIGO</th>
							<th>DESCRIPCION ARTICULO</th>
							<th>CANT</th>
						</tr>
					</thead>
					<tbody>
						@foreach($details as $detail)
					    	<tr class="odd">
	                			<td> {{ $detail->code }}</td>
	                			<td> {{ $detail->label }}</td>
	                			<td> {{ $detail->quantity }}</td>
	            			</tr>
	            		@endforeach
					</tbody>
		  	</table>
	  	<!--fin Items -->
	  	<br>
	  	<br>
	 	@endforeach
	  </th>
	 </tr>
​
	 <tr>
	  <th>
	  <!-- open cotizacion -->
	  	<table class="close">
	  		<tr>
		  		<th>
		  			<h4>Le invitamos a utilizar el sistema Aguila para hacer seguimiento de sus ventas </h4>
		  		</th>
	 			</tr>
	  	</table>
	  
	  </th>
	 </tr>
	</table>
</body>
</html>
