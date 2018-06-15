<?php

define('DSN', 'dbname=opendata user=guest');

$db = pg_connect(DSN);
if(!$db){
    echo 'error not connected';
    return;
}

$sql = <<<EOS
SELECT ST_AsGeoJson((geom)) AS geojson
FROM mcdnald2 
EOS;

$result = pg_prepare($db, '', $sql);
$result = pg_execute($db, '', []);

$json = [];
while(($row = pg_fetch_assoc($result)) != NULL){
    $json[] = json_decode($row['geojson']);
}

header('content-type: application/json; charset=utf-8');
echo json_encode($json);
