<?php

use ClickHouseDB\Client;

$client = new Client([
    'host' => 'tc_clickhouse',
    'port' => '8123',
    'username' => 'default',
    'password' => '',
]);
$client->database('htp');

$count = $_GET['count'] ?? 500;
$limit = $_GET['limit'] ?? 1_000;
while ($count--) {
    $client->select('SELECT * FROM opensky LIMIT ' . $limit)->rows();
}
