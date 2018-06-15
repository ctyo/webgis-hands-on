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

if($zoom < 13){
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
SELECT e_stat_t000876.*, ST_AsGeoJson((elevation.geom)) AS geojson
FROM elevation LEFT JOIN e_stat_t000876 ON elevation.g04d_001=e_stat_t000876.key_code
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
