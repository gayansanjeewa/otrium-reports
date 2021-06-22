<?php

use App\Repository\GMVRepository;
use App\Repository\Contract\GMVRepositoryInterface;
use App\Service\Contract\ReportingServiceInterface;
use App\Service\ReportingService;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [
    'connection' => [
        'dbname' => DI\env('DATABASE_NAME', 'otrium'),
        'user' => DI\env('DATABASE_USER', 'otrium'),
        'password' => DI\env('DATABASE_PASSWORD', 'otrium'),
        'host' => 'db',
        'driver' => 'pdo_mysql',
    ],
    'report_store' => __DIR__ . '/../web/reports',
    'vat_percentage' => .21,

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
    ReportingServiceInterface::class => DI\autowire(ReportingService::class),

];
