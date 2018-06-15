'strict'

var map = null;

function initMap() {
  map = new google.maps.Map($('#map').get(0), {
    center: {lat: 35.172899, lng: 136.887531},
    zoom: 15 
    });

    var circle_coordinate = {lat: 35.174044, lng: 136.892795};
    var circle = new google.maps.Circle({
      map: map,
      center: circle_coordinate,
      radius: 500,
      strokeColor: '#ff0000',
      fillColor: '#ff0000',
      fillOpacity: 0.5
    });
}

