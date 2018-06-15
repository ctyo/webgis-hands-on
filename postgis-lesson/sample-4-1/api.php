<?php

define('DSN', 'dbname=opendata user=guest');

$db = pg_connect(DSN);
if(!$db){
    echo 'error not connected';
    return;
}

$north = $_GET['n'];
$west = $_GET['w'];
$east = $_GET['e'];
$south = $_GET['s'];
$zoom = $_GET['zoom'];

if($zoom < 14){
  echo json_encode([]);
  exit;
}

$geom = 'POLYGON((';
$geom .= $west . ' ' . $north . ',';
$geom .= $west . ' ' . $south . ',';
$geom .= $east . ' ' . $south . ',';
$geom .= $east . ' ' . $north . ',';
$geom .= $west . ' ' . $north;
$geom .= '))';

$sql = <<<EOS
SELECT *, ST_AsGeoJson((geom)) AS geojson
FROM e_stat_2015 
WHERE ST_Intersects(geom, ST_GeomFromText($1, 4326)) = True
EOS;

$result = pg_prepare($db, '', $sql);
$result = pg_execute($db, '', [$geom]);

$json = [];
while(($row = pg_fetch_assoc($result)) != NULL){
    $json[] = [
      'data' => $row,
      'geojson' => json_decode($row['geojson'])
    ];
}

echo json_encode($json);
