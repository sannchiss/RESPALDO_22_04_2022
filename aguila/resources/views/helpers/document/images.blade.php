<div class="card border-light">
	<h5 class="card-header">Firma</h5>
	<div class="card-body">
		@foreach($signs as $sing)
			<img src="{{ asset($sing->path) }}" alt="..." class="img-thumbnail show-modal-image" width="100">
		@endforeach
	</div>
	<h5 class="card-header">Otros</h5>
	<div class="card-body">
		@foreach($others as $other)
			<img src="{{ asset($other->path) }}" alt="..." class="img-thumbnail show-modal-image" width="100">
		@endforeach
	</div>
</div>