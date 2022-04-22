<div class='float-right' >
        <a class='btn btn-primary show-detail' href='{{ route("{$baseRoute}.documents.document_detail",[$data->id,'type' => $data->data_type ]) }}' data-document={{ $data->document ?? $data->id}} title='Detalle del documento'>
            <span class='fas fa-box-open'></span>
        </a>
</div>


