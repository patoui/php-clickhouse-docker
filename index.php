<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

$driver = $_GET['driver'] ?? null;

if (!$driver) {
    die('No driver specified');
}

if ($driver === 'extension') {
    $time_start = microtime(true);
    require_once __DIR__ . '/src/extension.php';
    $time_end = microtime(true);
} elseif ($driver === 'http') {
    $time_start = microtime(true);
    require_once __DIR__ . '/src/http.php';
    $time_end = microtime(true);
} elseif ($driver === 'tcp') {
    $time_start = microtime(true);
    require_once __DIR__ . '/src/tcp.php';
    $time_end = microtime(true);
}

$time     = $time_end - $time_start;
$output   = "{$driver} - Execution time: {$time} seconds";
echo $output . PHP_EOL;
file_put_contents(__DIR__ . '/execution_results.txt', $output, FILE_APPEND);
