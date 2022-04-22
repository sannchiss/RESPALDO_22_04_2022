<!-- Modal -->
<div class="modal fade" id="ShowCredencial" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Datos de Credencial</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

<div class="modal-body">
<form id = "cambiarCuenta" autocomplete="off">
  <div class="form-row">
    <div class="col-md-9 mb-3">
      <label for="validationDefault01">Nombre Completo</label>
      <input type="text" class="form-control" name="nombre" id="name" placeholder="Nombre completo" value="" required>
    </div>
    
    <div class="col-md-4 mb-3">
    <input class=" form-control" type="hidden" name="hiddeUsuarioId" id="hiddeUsuarioId">

      <label for="validationDefaultUsername">Usuario</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroupPrepend2">@</span>
        </div>
        <input type="text" class="form-control" name="usuario" id="user" placeholder="Username" aria-describedby="inputGroupPrepend2" required>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <label for="validationDefaultUsername">Contraeña</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroupPrepend2">*</span>
        </div>
        <input type="text" class="form-control" name="password" id="password" placeholder="Password" aria-describedby="inputGroupPrepend2" required>
      </div>
    </div>

  </div>
  <div class="form-row">
    <div class="col-md-3 mb-3">
    <label for="inputEmail4">Email</label>
    <input type="email" class="form-control" name="email" id="email" placeholder="Email">
    </div>
    <div class="col-md-3 mb-3">
    <label for="validationDefault03">Modalidad</label>
      
      <select class="custom-select mr-sm-2" name="modalidad" id="modalidad">
        <option selected>Buscar</option>
        <option value="1">B2B</option>
        <option value="2">B2C</option>
      </select>
    </div>
  </div>


  <div class="form-row">
  <div class="col-md-4 mb-3">
  <label for="validationDefault03">Ejecutivo Comercial</label>
      <div class="input-group">
        
        <input class="typeaheadComercial form-control" type="text" name="ejecutivo" id="comercial">
        <input class=" form-control" type="hidden" name="hiddeComercialId" id="hiddeComercialId">

      </div>
    </div>

  </div>


  

</form>
</div><!--Fin modal-body-->
    
<div class="modal-footer">
     <button type="button" class="btn btn-primary editUsuario">Editar</button>
     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
</div>
</div>
</div>
</div>
