@extends('helpers.modalForm', [
          'title'       => 'Empleado',
          'titleInfo'   => 'ID#'.$data->id,
          'isForm'      => false,
          ])


@section('modal-content')
    <ul class="list-group">
        @include('helpers.listGroup', [
            'putUlTag' => false, 
            'list' => $data->getModelAttributes([
                'office'      => 'office_id',
                'type'        => 'employee_types_id',
                'code'        => 'code',
                'rut'         => 'rut',
                'name'        => 'name',
                'lastname'    => 'lastname',
                'phone'       => 'phone',
            ])
        ])

        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-2 text-right"> 
                    <strong> Acceso al sistema : </strong>
                </div>
                <div class="col-sm-10">
                    {{ $data->has_access ? 'Si' : 'No' }}
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