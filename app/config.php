<?php

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [
    'connection' => [
        'url' => sprintf(
            'mysql://%s:%s@%s:%s/%s',
            DI\env('MYSQL_USER'),
            DI\env('MYSQL_PASSWORD'),
            DI\env('DATABASE_HOST'),
            DI\env('DATABASE_PORT'),
            DI\env('DATABASE_NAME')
        )
    ],

    // Twig
    Environment::class => function () {
        $loader = new FilesystemLoader(__DIR__ . '/src/Views');
        return new Environment($loader, [
            'debug' => true,
        ]);
    },

    // Database
    Connection::class => function (ContainerInterface $c) {
        return DriverManager::getConnection($c->get('connection.url'));
    },
];
