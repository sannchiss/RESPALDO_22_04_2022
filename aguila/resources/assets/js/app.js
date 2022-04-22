
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.toastr = require('toastr');
require('chart.js');
require('canvas-gauges/gauge.min.js');
window.moment = require('moment');
window.datetimepicker = require('tempusdominus-bootstrap-4');

$.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, {
	icons: {
    	time: 'fas fa-clock',
    	date: 'fas fa-calendar',
    	up: 'fas fa-arrow-up',
    	down: 'fas fa-arrow-down',
    	previous: 'fas fa-chevron-left',
    	next: 'fas fa-chevron-right',
    	today: 'fas fa-calendar-check-o',
    	clear: 'fas fa-trash',
    	close: 'fas fa-times'
	} });

//window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example-component', require('./components/ExampleComponent.vue'));
/*
const app = new Vue({
    el: '#app'
});
*/

/* OFFCANVAS */
	$(document).on('click','.show-canvas', function(e){
		getDocumentsTable($(this));
		$('#main-canvas').addClass('offcanvas-activate');  
		$('#offcanvas').addClass('offcanvas-activate');
		window.scrollTo(0,0);
	});

	$(document).on('click','.close-canvas', function(){
		$('.show-attachs').popover('dispose');
	    $('#wrap-canvas .show-form').attr('href',"#");
	    $('#main-canvas').removeClass('offcanvas-activate');  
		$('#offcanvas').removeClass('offcanvas-activate');

	});

/*
 * Detenermina que tabla recargar
 * buscando data-sheet en la url
 */
function reloadTable(url, self){
	if(typeof self.data('only-action') !== 'undefined'){
		eval(self.data('action'));
		if(self.data('onlyAction') == true){
			return;
		}
	}
	let hasDataSheet = url.indexOf("data-sheet");
	if(hasDataSheet >= 0){
		tableSheet.ajax.reload(); //recarga tabla de fichas
	}
	else {
		table.ajax.reload(); //recarga tabla normal
	}
}

/*
 * Detenermina que modal mostrar
 * buscando data-sheet en la url
 */
function showModal(url){
	let hasDataSheet = url.indexOf("data-sheet");
	if(hasDataSheet >= 0){
		return '#modal-data-sheet';
	}	 
	else {
	return '#modal-form';
	}
}

/*
 * Añade un nuevo registro
 */
$(document).on('click','.add-record',function(e){
	e.preventDefault();
	let url  = $('#record-form').attr('action');
	let inputs = $('#record-form').serialize();
	let self = $(this);

    //borra errores
	$('#record-form .has-error').each(function(){
		$(this).find('.invalid-feedback strong').html('');
		$(this).find('input,select,.special-invalid').removeClass('is-invalid');
		$(this).removeClass('has-error');
	});
            
	axios.post(url, inputs)
		.then(function (response) {
			reloadTable(url, self);
			$('#modal-form').modal('hide');
			toastr.success( 'Guardado exitosamente..!');
		})
		.catch(error => {
			let errors = error.response.data.errors;
			$.each( errors , function( key, value ) {
				$('.'+key+'-input').addClass('has-error');
				$('.'+key+'-input input, .'+key+'-input select, .'+key+'-input .special-invalid').addClass('is-invalid');
				$('.'+key+'-input .invalid-feedback strong').html(value[0]); //showing only the first error.
			});
		});
});

/*
 * Actualiza un registro
 */
$(document).on('click','.update-record',function(e){
	e.preventDefault();
	let url  = $('#record-form').attr('action');
	let inputs = $('#record-form').serialize();
	let self = $(this);

	//borra errores
	$('#record-form .has-error').each(function(){
    	$(this).find('.invalid-feedback strong').html('');
    	$(this).find('input,select,.special-invalid').removeClass('is-invalid');
    	$(this).removeClass('has-error');
	});
            
	axios.put(url, inputs)
		.then(function (response) {
    		reloadTable(url, self);
    		$('#modal-form').modal('hide');
    		toastr.success( 'Actualizado exitosamente..!');
		})
		.catch(error => {
    		let errors = error.response.data.errors;
     		$.each( errors , function( key, value ) {
        		$('.'+key+'-input').addClass('has-error');
        		$('.'+key+'-input input, .'+key+'-input select, .'+key+'-input .special-invalid').addClass('is-invalid');
        		$('.'+key+'-input .invalid-feedback strong').html(value[0]); //showing only the first error.
    		});
		});
});

/*
 * Muestra formulario para editarlo o crear
 */
$(document).on('click', '.show-form', function(e){
	e.preventDefault();
	let url  = $(this).attr('href');

	axios.get(url)
		.then(function(response){
			$('#modal-form .modal-content').html(response.data);
			$('#modal-form').modal('show');
		})
});

/*
 * Muestra un determinado registro
 */
$(document).on('click', '.show-record', function(e){
	e.preventDefault();
	let url  = $(this).attr('href');
	let modal = showModal(url);

	axios.get(url)
		.then(function(response){
			$(modal+' .modal-content').html(response.data);
			$(modal).modal('show');
		})
});

/*
 * Muestra un modal de confirmacion de eliminacion de un registro
 */
$(document).on('click','.delete-record-comfirm', function(e){
	e.preventDefault();
	let href  = $(this).attr('href');
	$('#confirm-delete .delete-record').prop('href', href);
	$('#confirm-delete').modal('show');
});

/*
 * Elimina un registro
 */
$(document).on('click','.delete-record', function(e){
	e.preventDefault();
	let url  = $(this).attr('href');
	let self = $(this);
	axios.delete(url)
		.then(function (response) {
			reloadTable(url, self);
			$('#confirm-delete').modal('hide');
			toastr.success( 'Eliminado exitosamente..!');
		});
});

$(document).on('click','.engine-off', function(e){
	e.preventDefault();
	let url  = $(this).data('url');
	let param = {device_id:$(this).data('device_id')};
	axios.post(url,param)
		.then( function(response){
			toastr.success( response.data.message );
		})
});