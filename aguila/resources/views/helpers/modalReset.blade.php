@php
	$anotherTag = isset($anotherTag) ? $anotherTag : ''; 
@endphp
<div id='confirm-delete' class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
	        <div class="modal-header">
			    
			    <h5 class="modal-title">Confirmar Reseteo</h5>
			    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			        <span aria-hidden="true">&times;</span>
			    </button>
			</div>
			<div class="modal-body">
				<p>Estas a punto de resetear una ruta y sus documentos asociados, este proceso es irreversible.</p> 
				<h5> ¿Quieres proceder? </h5>
			</div>
			<div class="modal-footer">
			    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			    <a href="#" class="btn btn-danger action-modal delete-record" {!! $anotherTag !!}>Resetear</a>
			</div>
        </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

