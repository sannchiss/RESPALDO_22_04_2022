<div class="float-right">
	@if(in_array('edit', $show) && Permission::hasPermission($updateRoute, true))
		<a class="btn btn-warning show-form" href="{{ route($updateRoute,[$id]) }}" data-id={{ $id }} title="Editar">
		   <i class="fas fa-pencil-alt"></i>
		</a>
	@endif
	@if(in_array('view', $show)  && Permission::hasPermission($showRoute, true))
		<a class="btn btn-success show-record" href="{{ route($showRoute,[$id]) }}" data-id={{ $id }} title="Ver">
			<span class="fas fa-eye"></span>
		</a>
	@endif
	@if(in_array('delete', $show) && Permission::hasPermission($deleteRoute, true))
		<a class="btn btn-danger delete-record-comfirm" href="{{ route($deleteRoute,[$id]) }}" data-id={{ $id }} title="Eliminar">
		   <i class="fas fa-trash-alt"></i>
		</a>
	@endif
	@if(in_array('reset', $show) && Permission::hasPermission($resetRoute, true))
		<a class="btn btn-danger delete-record-comfirm" href="{{ route($resetRoute,[$id]) }}" data-id={{ $id }} title="Resetear">
		   <i class="fas fa-power-off"></i>
		</a>
	@endif

	@if(in_array('child', $show))
		<a class="btn btn-primary" href="{{ route($childRoute,['parent_id' => $id]) }}" title="Items">
			<span class="fas fa-th-list"></span>
        </a>
    @endif
    @if(in_array('sheet',$show))
    	<a class="btn btn-success show-sheet" href="{{ route($sheetRoute,['parent_id' => $id, 'type'=> $typeSheet ]) }}" data-id={{ $id }} data-type="{{ $typeSheet }}" datatitle="Fichas">
			<span class="fas fa-list-alt"></span>
        </a>
    @endif
     
    @if(in_array('document',$show) && Permission::hasPermission('pick_delivery.documents', true) )
    	<a class="btn btn-orange show-canvas" href="#" data-id="{{ $id }}"  data-vehicle_id ="{{ $vehicle_id }}" title="Ver Carga">
			<span class="fas fa-truck-loading"></span>
        </a>
    @endif

    @if(in_array('map',$show))
    	<a class="btn btn-success show-onmap" href="#" data-id="{{ $id }}" title="Ver en Mapa">
			<span class="fas fa-map-marked-alt"></span>
        </a>
	@endif
	
	@if(in_array('shutdown-car',$show) && $gps_type == 'TELTONIKA')
		<button class="btn btn-danger engine-off" data-device_id="{{ substr($id,1) }}" data-url="{{ route('monitoring.engineoffon') }}" title="Inmovilizar motor"><span class="fas fa-power-off"></span></button>
	@endif

    @if(in_array('exportPdf',$show))
    <a class="btn btn-danger" href="{{ route($exportPdfRoute,[$id]) }}" title="Exportar a PDF">
          <span class="fas fa-save-file"></span>PDF
    </a>
    @endif
</div>