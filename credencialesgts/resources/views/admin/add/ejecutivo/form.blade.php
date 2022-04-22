<h1 class="mt-4">Agregar Ejecutivo</h1>
             <div class="card-header">
                <form id="ejecutivoAgregar">
                {{ csrf_field() }}
                <div class="form-row">
                    <div class="col-5">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre Completo">
                    </div>
                    <div class="col-2">
                    <input type="text" name="telefono" class="form-control" placeholder="Telefono">
                    </div>
                    <div class="col-3">
                    <input type="text" name="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="col">
                    <button  type="buttom" class="btn btn-primary mb-2 addexecutive">Agregar</button>
                    </div>

                   

                </div>
  
                </div>
                </form>
                </div>

                <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-bordered" id="ejecutivos-table" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>id </th>
                                                <th>Nombre Completo</th>
                                                <th>Telefono</th>
                                                <th>Email</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                    </div>