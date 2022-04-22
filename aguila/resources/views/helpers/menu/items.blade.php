{{-- First Levels--}}
@if(is_null($item->menu_id))
	@if($html == '')
		@if($item->route != '#')
			<li class="nav-item">
				<a class="nav-link" href="{{ $item->route == '#' ? $item->route : route($item->route) }}">
					<i class="{{ $item->icon }}"></i><span class="sidebar-item">{{ $item->label }}</span></a>
		    </li>
	    @endif
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
@else
	{{-- Other Levels--}}
	@if($html == '')
		@if($item->route != '#')
			<li>
				<a class="dropdown-item" href="{{ $item->route == '#' ? $item->route : route($item->route) }}">
					<i class="{{ $item->icon }}"></i><span class="">{{ $item->label }}</span>
				</a>
			</li>
		@endif
	@else
		<li class="dropdown-submenu">
			<a  class="dropdown-item" href="{{ $item->route == '#' ? $item->route : route($item->route) }}">
				<i class="{{ $item->icon }}"></i><span class="">{{ $item->label }}</span>
			</a>
			<ul class="dropdown-menu">
				{!! $html !!}
			</ul>
		</li>
	@endif
@endif
