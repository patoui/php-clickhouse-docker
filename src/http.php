<?php

use ClickHouseDB\Client;

$session_id = sha1(uniqid('', true));
$client = new Client([
    'host' => 'tc_clickhouse',
    'port' => '8123',
    'username' => 'default',
    'password' => '',
]);
$client->useSession($session_id);

$client->write('CREATE DATABASE IF NOT EXISTS htp');
$client->write('USE htp');

$client->write('DROP TABLE IF EXISTS htp.opensky');
$client->write('CREATE TABLE htp.opensky
(
    callsign String,
    number String,
    icao24 String,
    registration String,
    typecode String,
    origin String,
    destination String,
    firstseen DateTime,
    lastseen DateTime,
    day DateTime,
    latitude_1 Float64,
    longitude_1 Float64,
    altitude_1 Float64,
    latitude_2 Float64,
    longitude_2 Float64,
    altitude_2 Float64
) ENGINE = MergeTree ORDER BY (origin, destination, callsign);');

$fp = fopen(dirname(__DIR__) . '/flight_data/flightlist_20200401_20200430.csv', 'rb');
$header = fgetcsv($fp);
if ($header === false) {
    die('Unable to read header row');
}

$data = [];
while (($row = fgetcsv($fp)) !== false) {
    $data[] = [
        $row[0],
        $row[1],
        $row[2],
        $row[3],
        $row[4],
        $row[5],
        $row[6],
        $row[7],
        $row[8],
        $row[9],
        (float) $row[10],
        (float) $row[11],
        (float) $row[12],
        (float) $row[13],
        (float) $row[14],
        (float) $row[15],
    ];
    if (count($data) > 999) {
        $client->insert('htp.opensky', $data, $header);
        $data = [];
    }
}

if (count($data) > 0) {
    $client->insert('htp.opensky', $data, $header);
}
