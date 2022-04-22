<div class='float-right'>
    <a class='btn  show-attachs btn-{{ in_array($data->status_code,['IN_DELIVERY','PENDING_DEPARTURE']) ? 'secondary disabled' : 'warning' }}'
        id="propover-show-{{ $data->id }}"
        href='{{ route("{$baseRoute}.document_images",[$data->id]) }}'
        data-id={{ $data->id }}
        data-placement='right' 
        data-content="<div class='lds-ripple' style='margin: 0 auto;'><div></div><div></div></div>"
        data-html='true' 
        data-title="<h4> Imagenes adjuntas <button type='button' data-id={{ $data->id }} class='propover-close close' aria-label='Close'><span aria-hidden='true'>&times;</span></button> </h4>"
        data-trigger="popover"
        data-template='<div class="popover popover-document" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-document-body popover-body" id="popover-content-{{ $data->id }}"></div></div>'>
        <span class='fas fa-images'></span>
    </a>
    <a class='btn show-map btn-{{ $data->status_code == 'IN_DELIVERY' ? 'success' : 'secondary disabled' }}' 
        href='{{ route("{$baseRoute}.document_detail",[$data->id]) }}' 
        data-document='{{ $data->document }}'
        data-lat={{ $data->lat }}
        data-lon={{ $data->lon }}
        data-vehicle='{{ $data->vehicle_id }}'
        data-address='{{ $data->address }}'
        data-customer='{{ $data->customer }}'
        title='Detalle del documento'>
        <span class='fas fa-map-marked-alt'></span>
    </a>
    
    <a class='btn btn-primary show-detail' href='{{ route("{$baseRoute}.document_detail",[$data->id,'type' => $data->data_type ]) }}' data-document={{ $data->document ?? $data->id}} title='Detalle del documento'>
        <span class='fas fa-box-open'></span>
    </a>
</div>