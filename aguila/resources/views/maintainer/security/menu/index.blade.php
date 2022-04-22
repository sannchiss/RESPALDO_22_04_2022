@extends('layouts.app')
@section('style')
    <link href="{{ asset('css/nestable.css') }}" rel="stylesheet">
@endsection
@section('content')
    @include('helpers.breadcrumb')
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h3 class="card-title">Menu 
                @if(Permission::hasPermission('create'))
                   <a type="button" class="btn btn-secondary btn-sm float-right show-form" href="{{ route('maintainer.security.menu.create') }}">
                        <i class="fas fa-plus" aria-hidden="true"></i> Añadir item
                    </a>
                @endif
            </h3>
            
            <div class="clearfix"></div>
        </div>
        <div class="card-body">
            <div class="dd" id="nestable">
                {!! $menu !!}
            </div>
        </div>
    </div>
    @include('helpers.modal',['id' => 'modal-form','class'=>'modal-lg'])
    @include('helpers.modalDelete',['anotherTag' => "data-only-action = 'true' data-action = '(refreshMenuIndex())'"])
@endsection

@section('script')
    <script src="{{ asset('js/nestable.js') }}"></script>

    <script type="text/javascript">
        var ddnestable;
        function drawNestableMenu(){
            ddnestable = $('.dd').nestable({ 
                dropCallback: function(details) {
               
                    var order = new Array();
                    $("li[data-id='"+details.destId +"']").find('ol:first').children().each(function(index,elem) {
                        order[index] = $(elem).attr('data-id');
                    });
                    
                    if (order.length === 0){
                        var rootOrder = new Array();
                        $("#nestable > ol > li").each(function(index,elem) {
                            rootOrder[index] = $(elem).attr('data-id');
                        });
                    }

                    axios.put("{{ route('maintainer.security.menu.update',['']) }}"+"/"+details.sourceId, 
                        {
                            reordering  : true,
                            source      : details.sourceId, 
                            destination : details.destId, 
                            order       : order,
                            rootOrder   : rootOrder
                        }
                    ).then(function (response) { 
                        if(response.data == 'ok'){
                            toastr.success( 'Actualizado correctamente..!');
                        }
                        else {
                            toastr.error( 'error al actualizar..!');
                        }
                    }) 
                 }
            });
        }

        function refreshMenuIndex(){
            axios.get("{{ route('maintainer.security.menu.index') }}")
                .then(function (response) {
                    $('.dd').html(response.data);
                    drawNestableMenu();
                })
        }

        drawNestableMenu()

    </script>
@endsection