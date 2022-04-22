<option value="">Seleccione ... </option>
@foreach ($data as $opt)
	<option value="{{ $opt->{$id} }}" {{ $opt->{$id} == $compare ? "selected" : "" }}>{{ $opt->{$label} }}</option>
@endforeach