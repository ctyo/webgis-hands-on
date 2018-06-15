<?php

define('DSN', 'dbname=opendata user=guest');

$north = $_GET['n'];
$west = $_GET['w'];
$east = $_GET['e'];
$south = $_GET['s'];
$zoom = $_GET['zoom'];

// 四角形で囲んでる感じ
$bounds = array();
$bounds[] = 'POLYGON((';
$bounds[] = $west.' '.$north.',';
$bounds[] = $west.' '.$south.',';
$bounds[] = $east.' '.$north.',';
$bounds[] = $east.' '.$south.',';
$bounds[] = $west.' '.$north;
$bounds[] = '))';

//echo implode("\n", $bounds);
//exit;


$db = pg_connect(DSN);
if(!$db){
    echo 'error not connected';
    return;
}

$sql = <<<EOS
SELECT ST_AsGeoJson((geom)) AS geojson
FROM mcdnald2
WHERE ST_Intersects(geom, ST_GeomFromText($1, 4326)) = True
EOS;

$result = pg_prepare($db, '', $sql);
$result = pg_execute($db, '', [implode("\n", $bounds)]);

$json = [];
while(($row = pg_fetch_assoc($result)) != NULL){
    $json[] = json_decode($row['geojson']);
}

header('content-type: application/json; charset=utf-8');
echo json_encode($json);
