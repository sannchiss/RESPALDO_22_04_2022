@extends('layouts.offcanvas')
@section('style')
<link href="{{ asset('css/pick_delivery.css') }}" rel="stylesheet">
  <style type="text/css">
    .input-search-text{
      height: 80px;
      font-size: 30px;
    }
    .button-search{
      font-size: 30px;
    }
    button.close{
      font-size: 40px;
    }
  </style>
@endsection
@section('content')

  <div class="container-fluid">

        <!--- formulario -->
        <form id="search-form" class="row">
           <input type="hidden" name="search_type" id="selection_search">
            <div class="col-md-10 offset-md-1">

              <!--Busquedad all input -->
              <div class="searching-input" id="all_search">
                <div class="form-group">
                  <div class="input-group mb-3 mt-3">
                    <select id="office_id" name="office_id" class="form-control input-office input-search-text">
                      <option value="0" selected>Todas</option>
                      @foreach($offices as $office)
                        <option value="{{ $office->id }}" {{ $office_id == $office->id ? 'selected':''}}>{{ $office->label }}</option>
                      @endforeach
                    </select>

                    <input type="text" class="form-control input-search-text" placeholder="Introduzca número de ruta"  name="searching" id="searching" aria-describedby="basic-addon2">
                   
                    <div class="input-group-append">
                      <button class="btn btn-outline-success button-search" type="button">Buscar</button>
                    </div>
                  </div>
                  <span class="invalid-feedback">
                      <strong></strong>
                  </span>
                </div>
              </div>

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
                  <table id="route-table" class="table table-striped dt-responsive" style="width:100%">
                    <thead class="thead-dark">
                      <tr>
                        
                        <th>Vehiculo</th>
                        <th>Ruta</th>
                        <th>Estado</th>
                        <th>Oficina</th>
                        <th>Creado</th>
                        <th>Actualizado</th>
                        <th></th>
                      </tr>
                    </thead>
                  </table>
              </div>
            </div>
          </div>
        </div>
  </div>

  @include('helpers.modal',['id' => 'modal-form','class'=>'modal-lg'])
  @include('helpers.modalReset')
@endsection

@section('script')
<script src="{{ asset('js/jquery-ui.js') }}"></script>
<script type="text/javascript">

  var table;
  $(document).on('click', '.button-search', function(e){
    e.preventDefault();
    $('.invalid-feedback').hide();
    $('.opacityAnimate').show();
    $('#search_result').hide('slide', {direction: 'up'});
    let inputs = $('#search-form').serialize();
    let url = "{{ route('maintainer.adjustment.route.index') }}?"+inputs;
    if(table){
          table.destroy();
      }
     //Datos al datatable
      table = $('#route-table').DataTable({
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
            {data: 'vehicle', name: 'vehicles.label'},
            {data: 'route', name: 'routes.code'},
            {data: 'status', name: 'statuses.label'},
            {data: 'office', name: 'offices.label'},
            {data: 'created_at', name: 'routes.created_at'},
            {data: 'updated_at', name: 'routes.update_at'},
            {data: 'action',    name: 'action', searchable: false, orderable: false},
          ]
      });

  });
</script>

@endsection
