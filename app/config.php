<?php

use App\Repository\GMVRepository;
use App\Repository\Contract\GMVRepositoryInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [
    'connection' => [
        'dbname' => 'otrium',
        'user' => 'otrium',
        'password' => 'otrium',
        'host' => 'db',
        'driver' => 'pdo_mysql',
    ],
    'report_store' => __DIR__ . '/../var/reports',

    // Twig
    Environment::class => function () {
        $loader = new FilesystemLoader(__DIR__ . '/../src/Views');
        return new Environment($loader, [
            'debug' => true,
        ]);
    },

    // Database
    Connection::class => function (ContainerInterface $c) {
        return DriverManager::getConnection($c->get('connection'));
    },

    // Bind an interface to an implementation
    GMVRepositoryInterface::class => DI\autowire(GMVRepository::class),

];
