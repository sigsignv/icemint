<?php

declare(strict_types=1);

namespace Sigsign\IceMint;

use Sigsign\IceMint\Controller\ViewController;
use Sigsign\IceMint\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class Router implements RouterInterface
{
    private ?UrlMatcher $matcher = null;
    private ?UrlGenerator $generator = null;

    public function __construct(
        private ?RouteCollection $routes = null,
        private RequestContext $context = new RequestContext(),
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

    public function getRouteCollection(): RouteCollection
    {
        return $this->routes();
    }

    public function getMatcher(): UrlMatcher
    {
        if ($this->matcher) {
            return $this->matcher;
        }

        $routes = $this->getRouteCollection();
        $this->matcher = new UrlMatcher($routes, $this->context);

        return $this->matcher;
    }

    public function getGenerator(): UrlGenerator
    {
        if ($this->generator) {
            return $this->generator;
        }

        $routes = $this->getRouteCollection();
        $this->generator = new UrlGenerator($routes, $this->context);

        return $this->generator;
    }

    public function match(string $pathinfo): array
    {
        return $this->getMatcher()->match($pathinfo);
    }

    public function matchRequest(Request $request): array
    {
        return $this->getMatcher()->matchRequest($request);
    }

    public function generate(
        string $name,
        array $parameters = [],
        int $referenceType = self::ABSOLUTE_PATH,
    ): string {
        return $this->getGenerator()->generate($name, $parameters, $referenceType);
    }

    public function getContext(): RequestContext
    {
        return $this->context;
    }

    public function setContext(RequestContext $context): void
    {
        $this->context = $context;
    }
}
