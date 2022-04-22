
		//propovers documents images
		$(document).on('click','.show-attachs', function(e){
			e.preventDefault();

			$('.show-attachs').popover('dispose');
			$(this).popover('show');

			let id  = $(this).data('id');
			let url = $(this).attr('href');

			axios.get(url)
	        .then(function(response){
				$('#popover-content-'+id).html(response.data);
			});
		});

		$(document).on('click','.propover-close', function(e){
			e.preventDefault();
			let id = $(this).data('id');
			$('#propover-show-'+id).popover('dispose');
		});

		var tableDocumentDetail;
		$(document).on('click','.show-detail', function(e){
			e.preventDefault();

			if(tableDocumentDetail){
	            tableDocumentDetail.destroy();
	        }
	        $('#document-detail #doc-detail').html( $(this).data('document') );
	        let url = $(this).attr('href');

	        //Datos al datatable
	        tableDocumentDetail = $('#document-detail-table').DataTable({
	            processing: true,
	            serverSide: true,
	            ajax: url,
	            pageLength: 50,
	            language: {
	                "url": dtLanguage,
	            },
	            columns: [
	                {data: 'code_product', name: 'products.code'},
	                {data: 'name_product', name: 'products.label'},
	                {data: 'quantity', name: 'quantity'},
	                {data: 'quantity_accepted', name: 'quantity_accepted'},
	                {data: 'quantity_rejected', name: 'quantity_rejected'},
	                {data: 'status', name: 'statuses.label'},
	                {data: 'status_reason', name: 'status_reasons.label'}
	            ]
	        });
			$('#document-detail').modal('show');
		});

		$(document).on('click','.show-modal-image',function(e){
			let url  = $(this).attr('src'); 
			let html = '<img src="'+ url +'" class="img-fluid" alt="Responsive image">';
			$('#document-modal-image .document-modal-image-content').html(html);
			$('#document-modal-image').modal('show');
		});