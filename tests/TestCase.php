<?php

namespace Tests;

use DI\Container;

class TestCase extends \PHPUnit\Framework\TestCase
{

    /** @var Container $container */
    protected mixed $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = require __DIR__ . '/../app/bootstrap.php';
        $this->container->injectOn($this);
    }
}