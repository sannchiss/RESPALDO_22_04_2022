<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<h1>Vehiculo  <span class="badge badge-success" id="doc-vehicle">--</span>
				<button type="button" class="close close-canvas" style="font-size: 40px;" aria-label="Close">
					<i aria-hidden="true" class="fas fa-arrow-circle-right"></i>
				</button>
			</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="card">
				<div class="card-header bg-info text-white">
					  <h3 class="card-title">
					   Datos generales
					  </h3>
				</div>
				<div class="card-body" id="doc-general">
				</div>
			</div>
			<br>
			<div class="card">
				<div class="card-body" id="doc-general">
					<div id="mapRoute"></div>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="card">
				<div class="card-header bg-danger text-white">
					  <h3 class="card-title">
					   Documentos
					  </h3>
				</div>
				<div class="card-body">
					<table id="document-table" class="table table-striped dt-responsive" style="width:100%">
						<thead class="thead-dark">
							<tr>
								<th></th>
								<th>Ruta</th>
								<th>Orden</th>
								<th>Documento</th>
								<th>Estado</th>
								<th>Cliente</th>
								<th>Fecha gestión</th>
								<th>Recibido</th>
								<th>Observacion</th>
							</tr>
						</thead>
					</table>
				</div>
		   </div>
		</div>

	</div>



</div>
