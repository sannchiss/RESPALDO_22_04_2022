{{-- 
	Genera un listGroup  
	@params #optional $putUlTag flag que determina si imprimir los ul
	@params #required $list array key, value con los campos que se vana  imprimir


	--}}
{{ isset($putUlTag) && $putUlTag == true ? '<ul class="list-group">' : '' }}
    @foreach($list as $rowKey => $rowData)
        <li class="list-group-item">
        	<div class="row">
	        	<div class="col-sm-3 text-right"> 
	        		<strong> {{ __(ucfirst($rowKey)) }} : </strong>
	        	</div>
	        	<div class="col-sm-9">
	        		{{ $rowData }}
	        	</div>
        	</div>
        </li>
    @endforeach
{{ isset($putUlTag) && $putUlTag == true ? '</ul>' : '' }}
