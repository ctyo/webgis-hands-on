<?php

define('DSN', 'dbname=opendata user=guest');

$north = $_GET['n'];
$west = $_GET['w'];
$east = $_GET['e'];
$south = $_GET['s'];
$zoom = $_GET['zoom'];
//if($zoom < 13){
//    echo json_encode([]);
//    exit;
//}
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
SELECT *, ST_AsGeoJson((geom)) AS geojson
FROM shelter
WHERE ST_Intersects(geom, ST_GeomFromText($1, 4326)) = True
LIMIT 100
EOS;

$result = pg_prepare($db, '', $sql);
$result = pg_execute($db, '', [implode("\n", $bounds)]);

$json = [];
while(($row = pg_fetch_assoc($result)) != NULL){
    $json[] = [
        'geojson' => json_decode($row['geojson']),
        'data' => $row,
    ];
}

header('content-type: application/json; charset=utf-8');
echo json_encode($json);
