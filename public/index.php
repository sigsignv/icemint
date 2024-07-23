<?php

declare(strict_types=1);

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__ . '/../vendor/autoload.php';

class ExampleController
{
    public function render(Request $request)
    {
        return new JsonResponse($request->attributes->all());
    }
}

$request = Request::createFromGlobals();

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
$dispatcher->addListener('response', function (\Sigsign\IceMint\ResponseEvent $event): void {
    $response = $event->getResponse();
    $headers = $response->headers;

    if (!$headers->has('Content-Length') && !$headers->has('Transfer-Encoding')) {
        $length = strlen($response->getContent());
        $headers->set('Content-Length', sprintf("%d", $length));
    }
}, -255);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new \Sigsign\IceMint\Framework($dispatcher, $matcher, $controllerResolver, $argumentResolver);
$response = $framework->handle($request);

$response->send();
