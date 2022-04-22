@extends('layouts.offcanvas')
@section('style')
<link href="{{ asset('js/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/pick_delivery.css') }}" rel="stylesheet">
  <style type="text/css">
    #mapDocument{
      height: 600px;
    }
    .popover-document {
      width: 300px;
      max-width: 300px;
      z-index: 1039;
        
    }
    .popover-document-body{
        max-height: 300px;
        overflow-x: scroll;
    }
    .select2-container .select2-selection--single {
      height: calc(2.19rem + 45px) !important;
      padding: 0.375rem 0.75rem;
      font-size: 30px;
      border-radius: 0px;
      }

    .pizzara {
      border: 10px solid #1a232a;
      border-radius: 5px;
      background: white; 
        
    }
    .input-search-text{
      height: 80px;
      font-size: 30px;
    }
    .button-search{
      font-size: 30px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 61px;
    }
    button.close{
      font-size: 40px;
    }
  </style>
@endsection
@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12" >
            <div class="jumbotron" style="/*background-image: url('{{ asset('img/cartoons/background.png')}}');  background-repeat: no-repeat; background-size: 100%;*/">
             
              <div class="row" >

                <div class="col-md-8">
                  <div class="row">
                    <div class="col-md-2">
                      <img src="{{ asset('img/sigecon.png') }}">
                    </div>
                    <div class="col-md-10">
                      <h1 class="display-4 text-center">¡Bienvenidos Vendedores a Águila!</h1>
                      <p class="lead text-center">Sistema de gestion y monitoreo de pedidos online.</p>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-2">
                      <img src="{{ asset('img/cartoons/prisila_apuntando.png') }}" class="img-fluid">
                      <div class="text-center">Vendedora: Priscila</div>
                    </div>
                    <div class="col-md-8 pizzara">
                      <br>
                      <h1 class="display-5 text-center">¿Necesitas ubicar un pedido?</h1>
                      <p class="lead text-center">Águila les ayudara a verificarlo</p>
                      <div class="row">
                        <div class="col-md-10 offset-md-1">
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text bg-danger border-danger" id="basic-addon1"><i class="fas fa-check text-white"></i></span>
                            </div>
                            <a type="text" data-selection="ROUTE" class="form-control border-danger show-canvas"  href="#"> Encuentra el pedido por número de <span class="font-weight-bold">Planilla de ruta</span></a>
                          </div>

                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text bg-warning border-warning" id="basic-addon1"><i class="fas fa-check text-white"></i></span>
                            </div>
                            <a type="text" data-selection="ORDER"  class="form-control border-warning show-canvas"  href="#"> Encuentra el pedido por número de <span class="font-weight-bold">Pedido</span></a>
                          </div>

                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text bg-primary border-primary" id="basic-addon1"><i class="fas fa-check text-white"></i></span>
                            </div>
                            <a type="text" data-selection="DOCUMENT"  class="form-control border-primary show-canvas"  href="#" > Encuentra el pedido por número de <span class="font-weight-bold">Guía o Factura</span></a>
                          </div>

                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text bg-success border-success" id="basic-addon1"><i class="fas fa-check text-white"></i></span>
                            </div>
                            <a type="text"  data-selection="CUSTOMER"  class="form-control border-success show-canvas"  href="#"> Encuentra el pedido por <span class="font-weight-bold">Cliente</span></a>
                          </div>
                          <br>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <img src="{{ asset('img/cartoons/priamo_apuntando.png') }}" class="img-fluid">
                      <div class="text-center">Vendedor: Priamo</div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 offset-md-1">
                  <img src="{{ asset('img/cartoons/eagle.png') }}" class="img-fluid">
                </div>
              </div>              
              {{-- <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
              <a class="btn btn-primary btn-lg" href="#" role="button"> more</a> --}}
            </div>
        </div>
    </div>
</div>


<!-- Modal document detail -->
<div id="document-detail" class="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width:70% !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Detalle de documento <span class="badge badge-secondary" id="doc-detail">--</span> </h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="document-detail-table" class="table table-striped dt-responsive nowrap" style="width:100%">
            <thead class="bg-info text-white">
              <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Cantidad aceptada</th>
                <th>Cantidad rechazada</th>
                <th>Estado</th>
                <th>Razon rechazo</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>
</div>

  <!-- Modal document image -->
  <div id="document-modal-image" class="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <div class="document-modal-image-content">
            <div class='lds-ripple' style='margin: 0 auto;'><div></div><div></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>

    <!-- Modal map -->
  <div id="document-modal-map" class="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <div class="document-modal-map-content">
            <div id="mapDocument"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('offcanvas-content')
  <div class="container-fluid">
    <button type="button" class="close close-canvas" aria-label="Close">
      <i class="fas fa-arrow-circle-right"></i>
    </button>

        <!--- formulario -->
        <form id="search-form" class="row">
           <input type="hidden" name="search_type" id="selection_search">
            <div class="col-md-10 offset-md-1">

              <!--Busquedad all input -->
              <div style="display:none;" class="searching-input" id="all_search">
                <div class="form-group">
                  <div class="input-group mb-3 mt-3">
                    <input type="text" class="form-control input-search-text" placeholder="Introduzca número de orden"  name="searching" id="searching" aria-describedby="basic-addon2">
                   
                    <div class="input-group-append">
                      <button class="btn btn-outline-success button-search" type="button">Buscar</button>
                    </div>
                  </div>
                  <span class="invalid-feedback">
                      <strong></strong>
                  </span>

                </div>
              </div>
              <!--Busquedad  cliente-->
              <div style="display:block" class="form-row" id="customer_search">
                <div class="form-group date_end-input date_start-input customer_id-input">
                  <div class="input-group mb-3 mt-3">
                    <select class="form-control input-search-text"  style="width: 50%;" data-placeholder="Seleccione un cliente" type="text" name="customer_id" id="customer_id"></select>

                    <input class="form-control input-search-text"  type="text" name="date_start" id="date_start" data-toggle="datetimepicker" data-target="#date_start" >
                    <input class="form-control input-search-text"  type="text"  name="date_end" id="date_end" data-toggle="datetimepicker" data-target="#date_end" >
                    <div class="input-group-append">
                      <button class="btn btn-outline-success button-search" type="button">Buscar</button>
                    </div>
                  </div>
                  <span class="invalid-feedback">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <!-- Fin bsuquedad cliente -->

            </div>
        </form>

        <!--FIN FORMULARIO -->
        <!--Resultado -->
        <div class="row">
          <div class="col-md-12">
            <h4 class="display-2 text-center opacityAnimate" style="display: none; color:#dc3545" >... Buscando<div class='lds-ripple' style='margin: 0 auto;'><div></div><div></div></div></h4>
            <div id="search_result" class="card" style="display: none">
              <div class="card-header bg-danger text-white">
                  <h3 class="card-title">Resultados</h3>
              </div>
              <div class="card-body">
                  <table id="document-table" class="table table-striped dt-responsive" style="width:100%">
                    <thead class="thead-dark">
                      <tr>
                        <th></th>
                        <th>Vehiculo</th>
                        <th>Ruta</th>
                        <th>Pedido</th>
                        <th>Guía o Factura</th>
                        <th>Estado</th>
                        <th>Cliente</th>
                        <th>Fecha entrega</th>
                        <th>Recibido</th>
                        <th>Observacion</th>
                      </tr>
                    </thead>
                  </table>
              </div>
            </div>
          </div>
        </div>
  </div>
@endsection

@section('script')
<script src="{{ asset('js/jquery-ui.js') }}"></script>
<script src="{{ asset('js/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('js/select2/js/i18n/es.js') }}"></script>
<script src="{{ asset('js/document.js') }}"></script>
<script src="{{ asset('js/map_document.js') }}"></script>
<script type="text/javascript">

  var urlVehiclePosition = '{{ route('quest.vehicle_position',['']) }}';

  $('#date_start').datetimepicker({
    locale: 'es', 
    format:'YYYY-MM-DD', 
    date: moment('{{ $date_start }}')
  });
  $('#date_end').datetimepicker({
    locale: 'es',
    format:'YYYY-MM-DD',
    date: moment('{{ $date_end }}') 
  });

  function initMap(){
    initOneMap();
  }
  function getDocumentsTable(element){
    //e.preventDefault();
    let selection = element.data('selection');
    let name = '' 
    $('.invalid-feedback').hide();
    $('#search_result').hide('slide', {direction: 'up'});
    //borra errores
    $('#search-form .has-error').each(function(){
        element.find('.invalid-feedback strong').html('');
        element.find('input,select,.special-invalid').removeClass('is-invalid');
        element.removeClass('has-error');
    });

    if(selection == ''){
      $('#all_search').hide();
      $('#customer_search').hide();
      return;
    }
    $('#selection_search').val(selection);
    if(selection == 'CUSTOMER'){
      $('#all_search').hide();
      $('#customer_search').show();
    } else{
      //aplico nombre del campo de busquedad
      switch(selection) {
          case 'ROUTE':
              name = 'Planilla Ruta';
              break;
          case 'ORDER':
              name = 'Pedido';
              break;
          case 'DOCUMENT':
              name = 'Guía o Factura'
              break;
          default:
              name = ''
              break;
      } 
      $('#searching').attr('placeholder','Introduzca número de ' + name);
      $('#searching').val('');
      $('#customer_search').hide()
      $('#all_search').show();
    }
  };

  var tableDocuments;
  $(document).on('click', '.button-search', function(e){
    e.preventDefault();
    $('.invalid-feedback').hide();
    $('.opacityAnimate').show();
    $('#search_result').hide('slide', {direction: 'up'});
    let inputs = $('#search-form').serialize();
    let url = "{{ route('quest.index') }}?"+inputs;
    if(tableDocuments){
          tableDocuments.destroy();
      }
     //Datos al datatable
      tableDocuments = $('#document-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: { 
            "url"  : url,
            "error": function(jqXHR, textStatus, errorThrown){
              let errors = jqXHR.responseJSON.errors
              $('.opacityAnimate').hide();
              $.each( errors , function( key, value ) {
                    $('.'+key+'-input').addClass('has-error');
                   /// $('.'+key+'-input input, .'+key+'-input select, .'+key+'-input .special-invalid').addClass('is-invalid');
                    $('.'+key+'-input .invalid-feedback strong').html(value[0]);
                    $('.'+key+'-input .invalid-feedback').show();
            });
        },
        "dataSrc": function ( json ) {
          $('#search_result').show('slide', {direction: 'up'});
          $('.opacityAnimate').hide();
          return json.data;
        }
      },
          pageLength: 50,
          language: {
              "url": dtLanguage,
          },
          columns: [
            {data: 'action',    name: 'action', searchable: false, orderable: false},
            {data: 'vehicle', name: 'vehicles.label'},
              {data: 'route', name: 'routes.code'},
              {data: 'order', name: 'documents.order_number'},
              {data: 'document', name: 'documents.code'},
              {data: 'status', name: 'statuses.label'},
              {data: 'customer', name: 'customer_branches.label'},
              {data: 'processed_date', name: 'documents.processed_date'},
              {data: 'received_by', name: 'documents.received_by'},
              {data: 'observation', name: 'documents.observation'}
          ]
      });

  });

var selectOption = {
dropdownParent: $("#search-form"),
language: "es",
  ajax: {
    url: "{{ route('quest.customers') }}",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        q: params.term, // search term
      };
    },
    processResults: function (data) {
      return {
        results: data
      };
    },
  },
  minimumInputLength: 2
}

$('#customer_id').select2(selectOption);
</script>
<script async defer
  src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API','') }}&callback=initMap">
</script>

@endsection
