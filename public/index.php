<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__ . '/../vendor/autoload.php';

$request = Request::createFromGlobals();

$routes = new RouteCollection();
$routes->add('view', new Route('/p/{slug}', [], ['slug' => '.*']));
$routes->add('edit', new Route('/edit'));

$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

try {
    $attr = $matcher->match($request->getPathInfo());
    $response = new Response(json_encode($attr, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE));
} catch (ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
}

$response->send();
