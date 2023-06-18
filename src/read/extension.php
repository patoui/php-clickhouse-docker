<?php

if (!extension_loaded('SeasClick')) {
    exit('Extension not loaded: SeasClick.' . PHP_SHLIB_SUFFIX);
}

$client = new SeasClick(['host' => 'tc_clickhouse', 'port' => 9000, 'compression' => true]);

$count = $_GET['count'] ?? 500;
$limit = $_GET['limit'] ?? 1_000;
while ($count--) {
    $client->select('SELECT * FROM ext.opensky LIMIT ' . $limit);
}
