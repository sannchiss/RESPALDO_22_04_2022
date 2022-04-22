<div class="card" style="width: 400px;">
	<div class="card-header bg-{{$data->condition}} text-white">
	 	<h5 class="text-center">Movil #{{$data->label}}</h5>
	 	@if($data->type == 'cell')
	 		<h5 class="text-center">Telefono #{{$data->code_cell}}</h5>
	 	@endif
	</div>
	<div class="card-body">
		<ul class="list-group list-group-flush">
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-6"><h6>Conductor</h6></div>
					<div  class="col-md-6">{{$data->fullname}}</div>
				</div>
			</li>
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-6"><h6>Fono</h6></div>
					<div  class="col-md-6">{{$data->phone_number}}</div>
				</div>
			</li>
			<li class="list-group-item">
					<div class="row">
						<div class="col-md-6"><h6>Estado Motor</h6></div>
						<div  class="col-md-6">{{ $data->ignition_status ? 'Encendido' : 'Apagado'}}</div>
					</div>
				</li>
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-6"><h6>Velocidad</h6></div>
					<div  class="col-md-6">{{$data->speed}}</div>
				</div>
			</li>
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-6"><h6>Sentido</h6></div>
					<div  class="col-md-6">{{$data->heading ?? '--'}}</div>
				</div>
			</li>
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-6"><h6>Última actualización</h6></div>
					<div  class="col-md-6">{{$data->date_time}}</div>
				</div>
			</li>
			@if(Permission::hasPermission('pick_delivery.documents', true) )
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-6" >
						<button class="btn btn-orange show-canvas" data-vehicle_id="{{$data->id}}"><span class="fas fa-truck-loading"></span> Ver carga</button>
					</div>
					<div class="col-md-6" >
						@if($data->type == 'gps' && $data->gps_type == 'TELTONIKA' )
							<button class="btn btn-danger engine-off" data-device_id="{{ $data->gps_device_id }}" data-url="{{ route('monitoring.engineoffon') }}"><span class="fas fa-power-off"></span> Inmovilizar motor</button>
						@endif
					</div>
				</div>
			</li>
			@endif
		</ul>
	</div>
</div>