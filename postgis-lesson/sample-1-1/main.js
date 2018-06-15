'strict'

var map = null;

function initMap() {
  map = new google.maps.Map($('#map').get(0), {
    center: {lat: 35.172899, lng: 136.887531},
    zoom: 15 
    });

  $.ajax({
    url: 'api.php',
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
  data.forEach(function(geom){
    var circle = new google.maps.Circle({
      map: map,
      center: {lat: geom.coordinates[1], lng: geom.coordinates[0]},
      radius: 100
    });
  });
}
