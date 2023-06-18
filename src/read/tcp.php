<?php

use OneCk\Client;

$client = new Client(
    'tcp://tc_clickhouse:9000',
    'default',
    '',
    'tcp',
    // ['tcp_nodelay' => true, 'persistent' => true]
);

$count = $_GET['count'] ?? 500;
$limit = $_GET['limit'] ?? 1_000;
while ($count--) {
    $client->query('SELECT * FROM opensky LIMIT ' . $limit);
}
