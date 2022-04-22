{{-- 
	Genera inputs para los forms
	@params #required $inputName nombre del input
	@params #optional $type tipo de input
	@params #optional $labelPosition posicion del label con respecto al input
--}}
@php
	$labelPosition = isset($labelPosition) ? $labelPosition : 'above';
	$type 		   = isset($type) 		   ? $type 	     	: 'text';
	$labelClass    = isset($labelClass)    ? $labelClass 	: '';
	$inputClass	   = isset($inputClass)    ? $inputClass 	: '';
	$optId		   = isset($optId) 		   ? $optId 		: '';
    $optLabel      = isset($optLabel)	   ? $optLabel 		: '';
    $optCompare    = isset($optCompare)    ? $optCompare 	: '';
    $optDatas      = isset($optDatas) 	   ? $optDatas 		: [];
    $otherTags 	   = isset($otherTags)	   ? $otherTags 	: '';
@endphp

@if($labelPosition =='left')
	@switch($type)
	    @case('text')
	    @case('password')
	        <div class="form-group {{ $inputName }}-input">
				<label for="{{ $inputName }}" class="{{ $labelClass }} col-form-label">{{ __(ucfirst($inputName)) }}</label>
				<div class="col-sm-{{ $inputLength }}">
					<input type="{{ $type }}" class="form-control {{ $inputClass}}" name="{{ $inputName }}" id="{{ $inputName }}" placeholder="{{ __(ucfirst($inputName)) }}" value="{{ $data->{$inputName} }}" {{ $otherTags }}>
					<span class="invalid-feedback">
						<strong></strong>
					</span>
				</div>
			</div>
	        @break

	    @default
	        Sin input...
	@endswitch
@else{{-- Default--}}
	@switch($type)
	    @case('text')
	    @case('password')
			<div class="form-group {{ $groupClass }} {{ $inputName }}-input" >
		     	<label for="{{ $inputName }}" class="{{ $labelClass }}">{{ __(ucfirst($inputName)) }}</label>
		    	<input type="{{ $type }}" class="form-control {{ $inputClass }}" name="{{ $inputName }}" id="{{ $inputName }}" placeholder="{{ __(ucfirst($inputName)) }}" value="{{ $data->{$inputName} }}" {{ $otherTags }}>
		     	<div class="invalid-feedback">
		        	<strong></strong>
		      	</div>
		    </div>

	        @break
	    @case('select')
	    	<div class="form-group {{ $groupClass }} {{ $inputName }}-input">
				<label for="{{ $inputName }}" class="{{ $labelClass }}">{{ __(ucfirst($inputName)) }}</label>
			 	<select class="form-control {{$inputClass}}" id="{{ $inputName }}" name="{{ $inputName }}" {{ $otherTags }}>
			    	<option value="">Seleccione ... </option>
			      	@foreach ($optDatas as $optData)
			          	<option value="{{ $optData->{$optId} }}" {{ $optData->{$optId} == $optCompare ? "selected" : "" }}>{{ $optData->{$optLabel} }}</option>
			      	@endforeach
			  	</select>
			  	<span class="invalid-feedback">
			    	<strong></strong>
			  	</span>
			</div>
			@break
	    @default
	        Sin input...
	@endswitch
@endif
