
@php
    $routes  = Request::route()->getName();
    $routes  = explode('.', (string)$routes, -1);
@endphp

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        @foreach ($routes as $route)
        	@if ($loop->last)
        	<li class="breadcrumb-item active">{{ __(ucfirst($route)) }}</li>
        	@else
        	<li class="breadcrumb-item">{{ __(ucfirst($route)) }}</li>
        	@endif
		@endforeach
    </ol>
</nav>
