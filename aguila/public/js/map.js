var INTERVAL = 30000;

var markerStore = {};
var infowindows = {};
var map = null
var activeInfoWindow = null;
var previusMarkAnimated = null;
function initMonitoringMap() {
    var moneda = {lat: -33.4429013, lng: -70.653934};
    map = new google.maps.Map(document.getElementById('map'), {
    	zoom: 12,
   		center: moneda
	});

	getMarkers();
}

function getMarkers() {
    $.get('/markers/'+office_id+'/'+device_type+'/'+condition, {}, function(res,resp) {
        for(var i=0, len=res.length; i<len; i++) {
            let row  = res[i];
        	var marker = null;
            //Do we have this marker already
            
            if(markerStore.hasOwnProperty(row.device_id)) {
                markerStore[row.device_id].setPosition(new google.maps.LatLng(row.lat,row.lon));
            } else {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(row.lat,row.lon),
                    title:row.name,
                    icon:"../img/map-icons/SetName.php?name="+row.label+"&type="+row.type+"&condition="+row.condition,
                    map:map
                }); 
                markerStore[row.device_id] = marker;
                infowindows[row.device_id] = new google.maps.InfoWindow({
                    content: "<div class='lds-ripple' style='margin: 0 auto;'><div></div><div></div></div>",
                    width: 300
                });
            }

            markerStore[row.device_id].setIcon("../img/map-icons/SetName.php?name="+row.label+"&type="+row.type+"&condition="+row.condition);
            if(previusMarkAnimated == row.device_id){
                putContentInfoWindows(row);
            }
            google.maps.event.addListener(markerStore[row.device_id], 'click', (function (data) {
				return function () {
                    //centro y zoom
					map.setZoom(16);
                    map.setCenter(markerStore[data.device_id].getPosition());
                    //baja al centrar para que se peuda ver el info windows
                    let divHeightOfTheMap = document.getElementById('map').clientHeight;
                    let offSetFromBottom = 100;
                    map.panBy(0, -(divHeightOfTheMap / 2 - offSetFromBottom));

                    //animacion
                    if(previusMarkAnimated != null) markerStore[previusMarkAnimated].setAnimation(null);
                    markerStore[data.device_id].setAnimation(google.maps.Animation.BOUNCE);
                    previusMarkAnimated = data.device_id;
                        
                    //infowindows
                    if (activeInfoWindow != null) activeInfoWindow.close();
    				activeInfoWindow = infowindows[data.device_id];
                    infowindows[data.device_id].open(map, markerStore[data.device_id]);
                    putContentInfoWindows(data);
                    //infowindows[data.device_id].setContent(openInfoWindows(data));
				}
			})(row));     
        }
    }, "json");
    window.setTimeout(getMarkers,INTERVAL);
}


function putContentInfoWindows(data){

    axios.get('/currentstatus/'+data.type+'/'+data.id+'/'+office_id)
    .then(function (response) {
        infowindows[data.device_id].setContent(response.data);
    })
    .catch(error => {
        infowindows[data.device_id].setContent("Error al traer los datos");
    });
}

$(document).on('click',".show-onmap", function(e){
	e.preventDefault();
	let id = $(this).data("id");
	new google.maps.event.trigger( markerStore[id], 'click' );
})


