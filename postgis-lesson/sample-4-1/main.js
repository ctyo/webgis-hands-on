'strict'

var map = null;
var objects = [];

function initMap() {
  map = new google.maps.Map($('#map').get(0), {
    center: {lat: 35.172899, lng: 136.887531},
    zoom: 15 
    });

  google.maps.event.addListener(map, 'idle', idleMaps);
}

function idleMaps()
{
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

function render(data){
  objects.forEach(function(o){
    o.setMap(null);
  });

  data.forEach(function(row){
    var coordinates = [];

    row.geojson.coordinates.forEach(function(polygons){
      polygons.forEach(function(polygon){
        polygon.forEach(function(coordinate){
          coordinates.push({lat: coordinate[1], lng: coordinate[0]});
        });
      });
    });

    var circle = new google.maps.Polygon({
      map: map,
      paths: coordinates
    });

    objects.push(circle);
  });
}

