<table id="" class="table table-striped">
	<tbody>
		<tr>
			<td colspan="2">Conductor</td>
			<td colspan="2"> {{ $data->driver or '--' }}</td>
		</tr>
		<tr>
			<td colspan="2">Fono</td>
			<td colspan="2"> {{ $data->phone_number or '--' }}</td>
		</tr>
		<tr>
			<td>Rutas</td>
			<td><h4><span class="badge badge-light">{{ $data->qty_routes or '--' }}</span></h4></td>

			<td>Documentos</td>
			<td><h4><span class="badge badge-light">{{ $data->qty_documents or '--' }}</span></h4></td>
		</tr>
		<tr>
			<td>En ruta</td>
			<td><h4><span class="badge badge-warning">{{ $data->in_delivery or '--' }}</span></h4></td>

			<td>Aceptados</td>
			<td><h4><span class="badge badge-success">{{ $data->accepted or '--' }}</span></h4></td>
		</tr>
		<tr>
			<td>Rechazados</td>
			<td><h4><span class="badge badge-danger">{{ $data->rejected or '--' }}</span></h4></td>
			<td>Rechazados parcial</td>
			<td><h4><span class="badge badge-orange">{{ $data->partial_rejection or '--' }}</span></h4></td>
		</tr>
		<tr>
			<td>Redespacho</td>
			<td><h4><span class="badge badge-info">{{ $data->redespaching or '--' }}</span></h4></td>
			<td>Pedidos</td>
			<td><h4><span class="badge badge-light">{{ $data->qty_orders or '--' }}</span></h4></td>
		</tr>

	</tbody>
</table>