@if($html == '')
	<li class="nav-item">
		<a class="nav-link" href="{{ $item->route == '#' ? $item->route : route($item->route) }}">
			<i class="{{ $item->icon }}"></i><span class="sidebar-item">{{ $item->label }}</span></a>
    </li>
@else
	<li class="nav-item dropright">
		<a class=" nav-link dropdown-toggle" href="{{ $item->route == '#' ? $item->route : route($item->route) }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="{{ $item->icon }}"></i><span class="sidebar-item">{{ $item->label }}</span>
       </a>
        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
			{!! $html !!}
		</ul>
	</li>
@endif