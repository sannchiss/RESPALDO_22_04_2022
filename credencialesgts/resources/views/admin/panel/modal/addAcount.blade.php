<div class="modal fade" id="addMoldalCredencial" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Cuenta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id ="addCuentaUsuario" autocomplete="off">
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Empresa:</label>
            <input class="typeaheadEmpresa form-control" type="text" name="empresa" id="Idempresa">
            <input class="form-control" type="hidden" name="hiddeEmpresa" id="hiddeEmpresaid">

          </div>
          <div class="form-row">
  <div class="col-md-6 mb-3">
      <label for="validationDefaultUsername">Cuenta TXA</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroupPrepend2">TXA</span>
        </div>
        <input type="text" name="acountTXA" class="form-control" id="txa" placeholder="Cuenta" aria-describedby="inputGroupPrepend2" required>
      </div>
    </div>

    <div class="col-md-6 mb-3">
      <label for="validationDefaultUsername">Cuenta GTS</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroupPrepend2">GTS</span>
        </div>
        <input type="text" name="acountGTS" class="form-control" id="gts" placeholder="Cuenta" aria-describedby="inputGroupPrepend2" required>
      </div>
    </div>

  </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary saveAcountUser">Agregar</button>
      </div>
    </div>
  </div>
</div>