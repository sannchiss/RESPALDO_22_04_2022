@php
    $route            = isset($route)            ? $route            : '';
    $actionClass      = isset($actionClass)      ? $actionClass      : '';
    $actionLabel      = isset($actionLabel)      ? $actionLabel      : '';
    $isForm           = isset($isForm)           ? $isForm           : true;
    $anotherActionTag = isset($anotherActionTag) ? $anotherActionTag : '';
@endphp

<div class="modal-header">
    <h5 class="modal-title">{{ $title }} <h4><span class='badge badge-info'>{{ $titleInfo }}</span></h4></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
	<div class="container-fluid">
        @if($isForm)
    	    <form id='record-form' method="POST" action="{{ $route }}" class="form-horizontal">
    	    	@yield('inputs')
    	    </form>
        @else
            @yield('modal-content')
        @endif
	</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    @if($isForm)
        <button type="button" class="btn btn-primary {{ $actionClass }}" {!! $anotherActionTag !!}>{{ $actionLabel}}</button>
    @endif
</div>