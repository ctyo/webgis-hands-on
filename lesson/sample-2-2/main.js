'strict'

var map = null;

function initMap() {
  map = new google.maps.Map($('#map').get(0), {
    center: {lat: 35.172899, lng: 136.887531},
    zoom: 15 
    });

  // circle
  var circle_coordinates = [
    {lat: 35.174044, lng: 136.892795},
    {lat: 35.168431, lng: 136.886906},
    {lat: 35.166475, lng: 136.881477},
  ];

  circle_coordinates.forEach(function(coordinate){
    var circle = new google.maps.Circle({
      map: map,
      center: coordinate,
      radius: 100
    });
  });
}

