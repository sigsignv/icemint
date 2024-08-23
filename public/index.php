<?php

declare(strict_types=1);

use Sigsign\IceMint\Router;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

require_once __DIR__ . '/../vendor/autoload.php';

$request = Request::createFromGlobals();
$requestStack = new RequestStack();

$router = new Router();
$routes = $router->routes();

$context = new RequestContext();
$matcher = new UrlMatcher($routes, $context);

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(
    new HttpKernel\EventListener\RouterListener($matcher, $requestStack, debug: false),
);
$dispatcher->addSubscriber(
    new HttpKernel\EventListener\ResponseListener('UTF-8'),
);
$dispatcher->addSubscriber(
    new HttpKernel\EventListener\ErrorListener(
        'Sigsign\IceMint\Controller\ErrorController::exception',
    ),
);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new \Sigsign\IceMint\Framework($dispatcher, $controllerResolver, $requestStack, $argumentResolver);
$framework = new HttpKernel\HttpCache\HttpCache(
    $framework,
    new HttpKernel\HttpCache\Store(__DIR__ . '/../cache'),
);
$response = $framework->handle($request);

$response->send();
