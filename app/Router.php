<?php

declare(strict_types=1);

namespace Sigsign\IceMint;

use Sigsign\IceMint\Controller\ViewController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class Router
{
    public function __construct(
        private ?RouteCollection $routes = null,
    ) {}

    public function routes(): RouteCollection
    {
        return $this->routes ?? $this->defaultRoutes();
    }

    private function defaultRoutes(): RouteCollection
    {
        $routes = new RouteCollection();

        $routes->add('top', new Route('/', [
            'page' => 'FrontPage',
            '_controller' => [ ViewController::class, 'show' ],
        ]));

        $routes->add('view', new Route('/p/{page}', [
            '_controller' => [ ViewController::class, 'show' ],
        ], [ 'page' => '.*' ]));

        $routes->add('edit', new Route('/edit', [
            '_controller' => [ ViewController::class, 'edit' ],
        ], methods: [ 'GET', 'HEAD' ]));

        $routes->add('post', new Route('/edit', [
            '_controller' => [ ViewController::class, 'update' ],
        ], methods: [ 'POST' ]));

        return $routes;
    }
}
