<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Panel Admin-Credenciales-TEST</title>
        <link href="../src/css/styles.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>

        @section('css')
        @endsection


    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="index.html">ADMIN CUENTAS</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" />
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">Settings</a>
                        <a class="dropdown-item" href="#">Activity Log</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="login.html">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="{{ url('/') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Panel Credenciales
                            </a>
                            <div class="sb-sidenav-menu-heading">Interface</div>

                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayoutsList" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                + Agregar Listado
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayoutsList" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ url('/company') }}">Empresa</a>
                                <a class="nav-link" href="{{ url('/executive') }}">Ejecutivo</a>
                                </nav>
                            </div>

                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Consultas
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{ url('/query') }}">Consulta Rapida</a>
                                  <!--  <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>-->
                                </nav>
                            </div>

                            
                          
                            
                    </div>
                 <!--   <div class="sb-sidenav-footer">
                        <div class="small">-</div>
                        Sannchiss
                    </div>-->
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        
                      <!--  <div class="row">
                        


                        
                        </div>
                        <div class="row">
                        
                        </div>-->

                        @yield('content')

                        
                        
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2020</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div><!--Fin de Content-->
        </div>


        <!-- Modal -->

<form id="formUsuario" autocomplete="off">       
<div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Agregar Usuarios</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

<div class="modal-body">

  <div class="form-row">
    <div class="col-md-9 mb-3">
      <label for="validationDefault01">Nombre Completo</label>
      <input type="text" name="name" class="form-control" id="validationDefault01" placeholder="Nombre completo" value="" required>
    </div>
    
    <div class="col-md-4 mb-3">
      <label for="validationDefaultUsername">Usuario</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroupPrepend2">@</span>
        </div>
        <input type="text" name="user" class="form-control" id="validationDefaultUsername" placeholder="Username" aria-describedby="inputGroupPrepend2" required>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <label for="validationDefaultUsername">Contraseña</label>
      <div class="input-group mb-3">
  <div class="input-group-prepend">
    <button class="btn btn-outline-secondary" type="button" id="button-aleatorio">Generar</button>
  </div>
  <input type="text" class="form-control" id="password123" name="password123"  aria-label="Example text with button addon" aria-describedby="button-addon1">
</div>
      
    </div>

  </div>



  <div class="form-row">
    <!--<div class="col-md-6 mb-3">
      <label for="validationDefault03">Empresa</label>
      
      <input class="typeaheadEmpresa form-control" type="text" name="empresa" id="Idempresa">
      <input class="form-control" type="text" name="hiddeEmpresa0" id="hiddeEmpresaid">
  

    </div>-->
    <div class="col-md-3 mb-3">
    <label for="inputEmail4">Email</label>
    <input type="email" name="email" class="form-control" id="inputEmail4" placeholder="Email">
    </div>
    <div class="col-md-3 mb-3">
    <label for="validationDefault03">Modalidad</label>
      
      <select class="custom-select mr-sm-2" id="modalidad" name="modality">
        <option selected>Buscar</option>
        <option value="B2B">B2B</option>
        <option value="B2C">B2C</option>
      </select>
    </div>
  </div>

 <!-- <div class="form-row">
  <div class="col-md-4 mb-3">
      <label for="validationDefaultUsername">Cuenta TXA</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroupPrepend2">TXA</span>
        </div>
        <input type="text" name="acountTXA" class="form-control" id="txaid" placeholder="Cuenta" aria-describedby="inputGroupPrepend2" required>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <label for="validationDefaultUsername">Cuenta GTS</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroupPrepend2">GTS</span>
        </div>
        <input type="text" name="acountGTS" class="form-control" id="gtsid" placeholder="Cuenta" aria-describedby="inputGroupPrepend2" required>
      </div>
    </div>

  </div>-->
  
  <div class="form-row">

  <div class="col-md-6 mb-3">
      <label for="validationDefault03">Ejecutivo Comercial</label>
      <select class="custom-select mr-sm-2" id="ejecutivoList" name="ejecutivo">
      <option  selected>Buscar</option>
      </select>
    </div>
  </div>

</div><!--Fin modal-body-->
    
<div class="modal-footer">
     <button type="button" class="btn btn-primary save">Agregar</button>
     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
</div>
</div>
</div>
</div>

</form>



        @yield('scripts')
        <script src="{{ asset('js/accionesjs.js') }}"></script>


        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
       
        <!--LIBRETIAS DATATABLE-->
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.22/b-1.6.5/b-html5-1.6.5/b-print-1.6.5/datatables.min.css"/>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.22/b-1.6.5/b-html5-1.6.5/b-print-1.6.5/datatables.min.js"></script>
        <!----------------------->

        <!--LIBRETIAS NOTIFICACIONES-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>



    </body>
</html>
