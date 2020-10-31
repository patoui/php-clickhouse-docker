<?php

declare(strict_types=1);

namespace Clickhouse;

use SeasClick;

class Clickhouse extends SeasClick
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        parent::__construct($config);
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}