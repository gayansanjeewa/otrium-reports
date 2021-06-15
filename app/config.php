<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [
    Environment::class => function () {
        return new Environment(new FilesystemLoader(__DIR__ . '/../src/Views'));
    },
];
