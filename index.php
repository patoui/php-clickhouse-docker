<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

if (!extension_loaded('SeasClick')) {
    exit('Extension not loaded: SeasClick.' . PHP_SHLIB_SUFFIX);
}

$client = new SeasClick(['host' => 'tc_clickhouse', 'port' => 9000, 'compression' => true]);

echo '<pre>';
var_dump($client->select('SHOW DATABASES'));
echo 'Finished';
