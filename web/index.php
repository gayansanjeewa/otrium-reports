<?php
session_start();

use App\Controller\HomeController;
use App\Controller\ReportController;
use DI\Container;
use FastRoute\RouteCollector;

/** @var Container $container */
$container = require __DIR__ . '/../app/bootstrap.php';

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->get('/', HomeController::class);
    $r->post('/reports', ReportController::class);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$route = $dispatcher->dispatch($httpMethod, $_SERVER['REQUEST_URI']);

switch ($route[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo '404 Not Found';
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo '405 Method Not Allowed';
        break;

    case FastRoute\Dispatcher::FOUND:
        $controller = $route[1];
        $parameters = $httpMethod == 'POST'? [$_POST] : $route[2];

        $container->call($controller, $parameters);
        break;
}
