<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

$driver = $_GET['driver'] ?? null;

if (!$driver) {
    die('No driver specified');
}

$action = $_GET['action'] ?? null;

if (!$action) {
    die('No action specified');
}

$filepath = __DIR__ . "/src/{$action}/{$driver}.php";

if (! file_exists($filepath)) {
    die('Unknown action and driver combo');
}

$time_start = microtime(true);
require_once $filepath;
$time_end = microtime(true);

$time     = $time_end - $time_start;
$output   = "{$action} - {$driver} - Execution time: {$time} seconds" . PHP_EOL;
echo $output;
file_put_contents(__DIR__ . '/execution_results.txt', $output, FILE_APPEND);
