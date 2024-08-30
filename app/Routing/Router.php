<?php

declare(strict_types=1);

namespace Sigsign\IceMint\Routing;

use Sigsign\IceMint\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

final class Router implements RouterInterface
{
    private ?UrlMatcher $matcher = null;
    private ?UrlGenerator $generator = null;

    public function __construct(
        private RouteCollection $routes,
        private RequestContext $context = new RequestContext(),
    ) {}

    public function getRouteCollection(): RouteCollection
    {
        return $this->routes;
    }

    public function getMatcher(): UrlMatcherInterface | RequestMatcherInterface
    {
        if ($this->matcher) {
            return $this->matcher;
        }

        $this->matcher = new UrlMatcher($this->routes, $this->context);
        return $this->matcher;
    }

    public function getGenerator(): UrlGeneratorInterface
    {
        if ($this->generator) {
            return $this->generator;
        }

        $this->generator = new UrlGenerator($this->routes, $this->context);
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

        if ($this->matcher) {
            $this->matcher->setContext($context);
        }
        if ($this->generator) {
            $this->generator->setContext($context);
        }
    }
}
