'strict'

var map = null;
var objects = [];

function render(data){
  objects.forEach(function(o){
    console.dir(o);
    o.setMap(null);
  });
  data.forEach(function(geom){
    var radius = 1000;
    if(geom.data.p20_005 > 0){
      radius = parseInt(geom.data.p20_005) / 1000;
    }

    var circle = new google.maps.Circle({
      map: map,
      center: {lat: geom.geojson.coordinates[1], lng: geom.geojson.coordinates[0]},
      radius: radius
    });
    objects.push(circle);
  });
}

  function idleMaps () {
    var zoom = map.getZoom();
    var bounds = map.getBounds();
    var ne = bounds.getNorthEast();
    var sw = bounds.getSouthWest();
    var params = 'zoom=' + zoom + '&n=' + ne.lat() + '&s=' + sw.lat() + '&e=' + ne.lng() + '&w=' + sw.lng();

    $.ajax({
      url: 'api.php?' + params,
      type: 'GET',
      dataType: 'json'
    })
    .done(function(data){
      console.log(data);
      render(data);
    })
    .fail(function(){
      console.log('error');
    });
  }


function initMap() {
  map = new google.maps.Map($('#map').get(0), {
    center: {lat: 35.172899, lng: 136.887531},
    zoom: 15 
    });
  google.maps.event.addListener(map, 'idle', idleMaps);
}