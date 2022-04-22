@extends('helpers.modalForm', [
    'title'       => 'Permiso',
    'titleInfo'   => 'ID#'.$data->id,
    'isForm'      => false,
])

@section('modal-content')
    <ul class="list-group">
        @include('helpers.listGroup', [
            'putUlTag' => false, 
            'list' => $data->getModelAttributes([
                'code'        => 'code',
                'label'       => 'label', 
                'root_route'  => 'root_route',
            ])
        ])
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-2 text-right"> 
                    <strong> {{ __('Permissions') }} : </strong>
                </div>
                <div class="col-sm-10">
                     @foreach($data->permissions as $key => $value)
                        @if( $value['checked'] == 'checked')
                            <h5 class="d-inline"><span class="badge badge-success">{{ __($key) }}</span></h5>
                        @endif
                    @endforeach
                </div>
            </div>
        </li>
        @include('helpers.listGroup', [
            'putUlTag' => false, 
            'list' => $data->getModelAttributes([
                'created_at'  => 'created_at',
                'updated_at'  => 'updated_at'
            ])
        ])
    </ul>


@endsection