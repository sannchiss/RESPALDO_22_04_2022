/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 171);
/******/ })
/************************************************************************/
/******/ ({

/***/ 171:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(172);


/***/ }),

/***/ 172:
/***/ (function(module, exports) {


//propovers documents images
$(document).on('click', '.show-attachs', function (e) {
	e.preventDefault();

	$('.show-attachs').popover('dispose');
	$(this).popover('show');

	var id = $(this).data('id');
	var url = $(this).attr('href');

	axios.get(url).then(function (response) {
		$('#popover-content-' + id).html(response.data);
	});
});

$(document).on('click', '.propover-close', function (e) {
	e.preventDefault();
	var id = $(this).data('id');
	$('#propover-show-' + id).popover('dispose');
});

var tableDocumentDetail;
$(document).on('click', '.show-detail', function (e) {
	e.preventDefault();

	if (tableDocumentDetail) {
		tableDocumentDetail.destroy();
	}
	$('#document-detail #doc-detail').html($(this).data('document'));
	var url = $(this).attr('href');

	//Datos al datatable
	tableDocumentDetail = $('#document-detail-table').DataTable({
		processing: true,
		serverSide: true,
		ajax: url,
		pageLength: 50,
		language: {
			"url": dtLanguage
		},
		columns: [{ data: 'code_product', name: 'products.code' }, { data: 'name_product', name: 'products.label' }, { data: 'quantity', name: 'quantity' }, { data: 'quantity_accepted', name: 'quantity_accepted' }, { data: 'quantity_rejected', name: 'quantity_rejected' }, { data: 'status', name: 'statuses.label' }, { data: 'status_reason', name: 'status_reasons.label' }]
	});
	$('#document-detail').modal('show');
});

$(document).on('click', '.show-modal-image', function (e) {
	var url = $(this).attr('src');
	var html = '<img src="' + url + '" class="img-fluid" alt="Responsive image">';
	$('#document-modal-image .document-modal-image-content').html(html);
	$('#document-modal-image').modal('show');
});

/***/ })

/******/ });