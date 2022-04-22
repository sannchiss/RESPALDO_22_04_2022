var INTERVAL = 30000;
var documentMarkers = {};
var routeMarkers = {};
var mapDocument = null;
var mapRoute    = null;
var activeDocumentMap = null;
var activeRoutetMap = null;
var documentInfowindows = {};
var routeActiveInfoWindow = null;
var routeInfoWindows ={};


var isTwo = false;
function initTwoMap() {
    var moneda = {lat: -33.4429013, lng: -70.653934};
    mapDocument = new google.maps.Map(document.getElementById('mapDocument'), {
    	zoom: 12,
   		center: moneda
	});

	mapRoute = new google.maps.Map(document.getElementById('mapRoute'), {
    	zoom: 12,
   		center: moneda
	});

	isTwo = true;
}

function initOneMap() {
    var moneda = {lat: -33.4429013, lng: -70.653934};
    mapDocument = new google.maps.Map(document.getElementById('mapDocument'), {
    	zoom: 12,
   		center: moneda
	});
}
$(document).on('click','.close-canvas', function(){
	activeRoutetMap = null;
});

$(document).on('click','.show-map', function(e){
	e.preventDefault();
	
	CleanDocumentMarkers()

	let lat 	 = $(this).data('lat');
	let lon 	 = $(this).data('lon');
	let vehicle_id  = $(this).data('vehicle');
	let customer = $(this).data('customer');    
	let address  = $(this).data('address'); 
	activeDocumentMap = vehicle_id;

	documentMarkers['customer'] = new google.maps.Marker({
        position: new google.maps.LatLng(lat,lon),
        title:customer,
        map:mapDocument
    }); 

    documentMarkers['customer'].setIcon("../img/map-icons/33x45/customer-danger.png");
    documentInfowindows = new google.maps.InfoWindow({
        content: "<h5>"+ customer +"<br><small class='text-muted'>"+address+"</small></h5>",
        width: 300
    });
    documentInfowindows.open(mapDocument, documentMarkers['customer']);

	$('#document-modal-map').modal('show');

	if( isTwo == false){
    	getDocumentVehicleMarkers(vehicle_id);
	}else{
		fixingMarkersDisplay(documentMarkers,mapDocument);
	}
	
});

$('#document-modal-map').on('hide.bs.modal', function (e) {
 	activeDocumentMap = null;
});

//limpia los marcadores de documentos
function CleanDocumentMarkers(){
	if(documentMarkers.hasOwnProperty('customer')){
		documentMarkers['customer'].setMap(null);
		if( isTwo == false){
			documentMarkers['vehicle'].setMap(null);
			documentMarkers = {};
		}
	}
}

function getDocumentVehicleMarkers(vehicle_id){
	if(activeDocumentMap == vehicle_id ){
		axios.get(urlVehiclePosition +"/" + vehicle_id)
		        .then(function(response){
		        	position = response.data.position;
		        	if(documentMarkers.hasOwnProperty('vehicle')) {
	                	documentMarkers['vehicle'].setPosition(new google.maps.LatLng(position.lat,position.lon));
	            	} else {
						documentMarkers['vehicle'] = new google.maps.Marker({
					        position: new google.maps.LatLng(position.lat,position.lon),
					        map:mapDocument
					    });
					    documentMarkers['vehicle'].setIcon("../img/map-icons/SetName.php?name="+position.label+"&type=gps&condition="+position.condition);
					}
					fixingMarkersDisplay(documentMarkers,mapDocument);
				});
		setTimeout(function(){ 
			getDocumentVehicleMarkers(vehicle_id)
		}, INTERVAL);
	}
}
//marcadores de ruta
function getRouteClientsMarkers(url,vehicle_id){
	activeRoutetMap = vehicle_id 
	$.each(routeMarkers, function(index, value){
		value.setMap(null);
	});
	routeMarkers = {};
	
	$.each(documentMarkers, function(index, value){
		value.setMap(null);
	});
	documentMarkers = {};
	routeInfoWindows = {};

	axios.get(url)
		.then(function(response){
			  customers = response.data.customers;
			  for(var i=0, len=customers.length; i<len; i++) {
			  	let index = 'c'+i;
				routeMarkers[index] = new google.maps.Marker({
					position: new google.maps.LatLng(customers[i].lat,customers[i].lon),
					map:mapRoute
				});
				routeMarkers[index].setIcon("../img/map-icons/33x45/customer-danger.png");
				routeInfoWindows[index] = new google.maps.InfoWindow({
                    content: "<h5>"+ customers[i].customer +"<br><small class='text-muted'>"+customers[i].address+"</small></h5><div>Guias & facturas: "+ customers[i].documents +"</div>",
                    width: 300
                });

				routeMarkers[index].addListener('click', function() {
					if (routeActiveInfoWindow != null){ 
						routeActiveInfoWindow.close();
					}
    				routeActiveInfoWindow = routeInfoWindows[index];
                    routeInfoWindows[index].open(mapRoute, routeMarkers[index]);
		        });
			}

			getVehicle(vehicle_id, customers);
		});
}

function fixingMarkersDisplay(markers, map){

	if(Object.keys(markers).length > 0){
		let bounds = new google.maps.LatLngBounds();
		$.each(markers, function(index, value){
			bounds.extend(value.getPosition());
		});
		map.fitBounds(bounds);
	}
}
//marcadores de vehiculos
function getVehicle(vehicle_id, customers){
	if(activeRoutetMap == vehicle_id ){
		axios.get(urlVehiclePosition +"/" + vehicle_id)
			.then(function(response){
			    position = response.data.position;
			    //ruta
				if(routeMarkers.hasOwnProperty('vehicle')) {
		            routeMarkers['vehicle'].setPosition(new google.maps.LatLng(position.lat,position.lon));
		        } else {
					routeMarkers['vehicle'] = new google.maps.Marker({
						position: new google.maps.LatLng(position.lat,position.lon),
						map:mapRoute
					});
					routeMarkers['vehicle'].setIcon("../img/map-icons/SetName.php?name="+position.label+"&type=gps&condition="+position.condition);
				}
				fixingMarkersDisplay(routeMarkers,mapRoute);

			    ///documento
			    if(documentMarkers.hasOwnProperty('vehicle')) {
		            documentMarkers['vehicle'].setPosition(new google.maps.LatLng(position.lat,position.lon));
		        } else {
					documentMarkers['vehicle'] = new google.maps.Marker({
						position: new google.maps.LatLng(position.lat,position.lon),
						map:mapDocument
					});
					documentMarkers['vehicle'].setIcon("../img/map-icons/SetName.php?name="+position.label+"&type=gps&condition="+position.condition);
				}
				fixingMarkersDisplay(documentMarkers,mapDocument);
				
			});

		setTimeout(function(){ 
			getVehicle(vehicle_id, customers)
		}, INTERVAL);
	}
}

