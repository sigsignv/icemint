<?php

declare(strict_types=1);

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__ . '/../vendor/autoload.php';

class ExampleController
{
    public function render(Request $request)
    {
        $response = new JsonResponse($request->attributes->all());
        $response->setTtl(10);

        return $response;
    }
}

$request = Request::createFromGlobals();
$requestStack = new RequestStack();

$routes = new RouteCollection();
$routes->add('view', new Route('/p/{slug}', [
    '_controller' => 'ExampleController::render',
], ['slug' => '.*']));
$routes->add('edit', new Route('/edit', [
    '_controller' => 'ExampleController::render',
]));

$context = new RequestContext();
$matcher = new UrlMatcher($routes, $context);

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher, $requestStack));
$dispatcher->addSubscriber(new HttpKernel\EventListener\ResponseListener('UTF-8'));

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new \Sigsign\IceMint\Framework($dispatcher, $controllerResolver, $requestStack, $argumentResolver);
$framework = new HttpKernel\HttpCache\HttpCache(
    $framework,
    new HttpKernel\HttpCache\Store(__DIR__ . '/../cache'),
);
$response = $framework->handle($request);

$response->send();
