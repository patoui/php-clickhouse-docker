<?php

if (!extension_loaded('SeasClick')) {
    exit('Extension not loaded: SeasClick.' . PHP_SHLIB_SUFFIX);
}

$client = new SeasClick(['host' => 'tc_clickhouse', 'port' => 9000, 'compression' => true]);

$client->execute('CREATE DATABASE IF NOT EXISTS ext');
$client->execute('USE ext');

$client->execute('DROP TABLE IF EXISTS opensky');
$client->execute('CREATE TABLE opensky
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

$fp = fopen(dirname(__DIR__, 2) . '/flight_data/flightlist_20200401_20200430.csv', 'rb');
$has_skipped_header = false;

$header = fgetcsv($fp);

if ($header === false) {
    die('Unable to read header row');
}

$client->writeStart('opensky', $header);

$data = [];
while (($row = fgetcsv($fp)) !== false) {
    $data[] = $row;
    if (count($data) > 999) {
        $client->write($data);
        $data = [];
    }
}

if (count($data) > 0) {
    $client->write($data);
}

$client->writeEnd();
