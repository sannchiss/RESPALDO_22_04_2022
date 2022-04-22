<div class='float-right' >
    <a class='btn btn-primary show-detail' href='{{ route("{$baseRoute}.vehicles.route_detail",[$data->id, 'date_start' => $date_start, 'date_end' => $date_end ]) }}' data-document={{ $data->code ?? $data->label}} title='Detalle Ruta'>
        <span class='fas fa-route'></span>
    </a>
</div>

