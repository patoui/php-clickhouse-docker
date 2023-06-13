<?php

use OneCk\Client;

$client = new Client(
    'tcp://tc_clickhouse:9000',
    'default',
    '',
    'default',
    // ['tcp_nodelay' => true, 'persistent' => true]
);

$client->query('CREATE DATABASE IF NOT EXISTS tcp');
$client->query('USE tcp');

$client->query('DROP TABLE IF EXISTS tcp.opensky');
$client->query('CREATE TABLE tcp.opensky
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
) ENGINE = MergeTree ORDER BY (origin, destination, callsign)');

$fp = fopen(dirname(__DIR__) . '/flight_data/flightlist_20200401_20200430.csv', 'rb');

$header = fgetcsv($fp);
if ($header === false) {
    die('Unable to read header row');
}

$client->writeStart('opensky', $header);

$data = [];
while (($row = fgetcsv($fp)) !== false) {
    $data[] = [
        'callsign' => $row[0],
        'number' => $row[1],
        'icao24' => $row[2],
        'registration' => $row[3],
        'typecode' => $row[4],
        'origin' => $row[5],
        'destination' => $row[6],
        'firstseen' => $row[7],
        'lastseen' => $row[8],
        'day' => $row[9],
        'latitude_1' => $row[10],
        'longitude_1' => $row[11],
        'altitude_1' => $row[12],
        'latitude_2' => $row[13],
        'longitude_2' => $row[14],
        'altitude_2' => $row[15],
    ];
    if (count($data) > 999) {
        $client->writeBlock($data);
        $data = [];
    }
}

if (count($data) > 0) {
    $client->writeBlock($data);
}

$client->writeEnd();
