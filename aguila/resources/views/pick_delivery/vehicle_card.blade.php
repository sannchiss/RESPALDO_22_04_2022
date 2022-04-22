@php

	$documentsDevide = 1;
	$fraction = 0;
	//esto arregla la variacion del indicador
	if($route->qty_document >= 6){
		$divideDecimal = $route->qty_document / 6;
		$whole = floor($divideDecimal);
		$fraction = $divideDecimal - $whole;
		$documentsDevide = $whole;
	}

	$forBucle = $route->qty_document >= 6 ? 5 : $route->qty_document - 1;
	$ticks = "0";
	$sum = 0;
	for ($i=0; $i < $forBucle; $i++) { 
		$sum += $documentsDevide;
		$ticks .= ",{$sum}";
	}
	$ticks .= ",{$route->qty_document}";
	$document_processed = $route->redespaching + $route->rejected + $route->accepted + $route->partial_rejection;
	$acceptedWithFraction = $route->accepted  + ($fraction > 0 ? 0.5 : 0);
	$toHighlights = $route->accepted > 0 ? $acceptedWithFraction : 0;
	//$toHighlights = $toHighlights > 0 ? $toHighlights : 0;
	$document_processedWithFraction = $document_processed + ($fraction > 0 ? 0.5 : 0);
	$value = $document_processedWithFraction > 0 ? $document_processedWithFraction : 0;//> 0 ? $document_processed - $fraction : 0


	$tooltip = "<h6>Total Rutas: <span class='t_routes'>{$route->count_routes}</span></h6>
				<h6>Total documentos: <span class='t_documents'>{$route->qty_document}</span></h6>
				<h6>Aceptados: <span class='t_accepted'>{$route->accepted}</span></h6>
				<h6>procesados: <span class='t_processed'>{$document_processed}</span></h6>";
@endphp
<div class="col-md-3">
	<div class="card" id='vehicle-{{$route->vehicle_id}}'>
			<div class="card-body">
			 <div class="row">
			 	<div class="col-md">
			 		<div class="row">
			 			<div class="col-lg-12">
							<button class="btn btn-success w-100 show-canvas"
								data-date_start = "{{$date_start}}"
								data-date_end 	= "{{$date_end}}"
								data-vehicle_id = "{{$route->vehicle_id}}">
								{{$route->vehicle_label}}
							</button>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<h6>Primera <br><small class="text-muted" class="first_time">{{ $route->first_time or '-'}}</small> </h6>
						</div>
						<div class="col-lg-12">
							<h6>Última <br><small class="text-muted" class="last_time">{{ $route->last_time or '-'}}</small></h6>
						</div>
					</div>
				</div>
				<div class="col-md">
					<div class="gouge-hide">
						<canvas data-type="radial-gauge"
									  data-toggle="tooltip" data-placement="top"
									  title="{{$tooltip}}" 
									  data-html="true"
									  data-width="250"
									  data-height="250"
									  data-value="{{$value}}"
									  data-min-value="0"
									  data-start-angle="90"
									  data-ticks-angle="180"
									  data-value-box="false"
									  data-color-numbers="#212529"
									  data-max-value="{{$route->qty_document}}"
									  data-major-ticks="{{$ticks}}"
									  data-minor-ticks="2"
									  data-stroke-ticks="true"
									  data-highlights='[{"from": 0, "to": {{ $toHighlights }}, "color": "rgba(40, 167, 69, 1)"}]'
									  data-color-plate="#fff"
									  data-border-shadow-width="0"
									  data-borders="false"
									  data-needle-type="line"
									  data-needle-width="3"
									  data-needle-circle-size="20"
									  data-needle-circle-outer="false"
									  data-needle-circle-inner="false"
									  data-animation-duration="1500"
									  data-animation-rule="linear"></canvas>
						<div class="gauge-status-circle">
							{{-- Rechazado--}}
							<div data-toggle="tooltip" data-placement="bottom" title="Rechazos Totales"  class="status-circle rejected_circle {{ $route->rejected > 0 ? 'status-1' : '' }}">
								<span>{{ $route->rejected }}</span>
							</div>
							{{-- Rechazo Parcial --}}
							<div data-toggle="tooltip" data-placement="bottom" title="Rechazos parciales" class="status-circle mid-status partial_rejection_circle {{ $route->partial_rejection > 0 ? 'status-2' : '' }}">
								<span>{{ $route->partial_rejection }}</span>
							</div>
							{{--  REDESPACHING --}}
							<div data-toggle="tooltip" data-placement="bottom" title="Redespachos" class="status-circle redespaching_circle {{ $route->redespaching > 0 ? 'status-3' : '' }}">
								<span>{{ $route->redespaching }}</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>