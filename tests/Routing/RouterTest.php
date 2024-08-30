<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Sigsign\IceMint\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouterTest extends TestCase
{
    protected RouteCollection $routes;

    protected function setUp(): void
    {
        $r = new RouteCollection();
        $r->add("user", new Route("/user/{name}"));

        $this->routes = $r;
    }

    public function testGetRouteCollection()
    {
        $router = new Router($this->routes);
        $this->assertEquals($router->getRouteCollection(), $this->routes);
    }

    public function testGetMatcher()
    {
        $router = new Router($this->routes);
        $matcher = $router->getMatcher();
        $this->assertInstanceOf(UrlMatcherInterface::class, $matcher);
        $this->assertInstanceOf(RequestMatcherInterface::class, $matcher);
    }

    public function testGetGenerator()
    {
        $router = new Router($this->routes);
        $generator = $router->getGenerator();
        $this->assertInstanceOf(UrlGeneratorInterface::class, $generator);
    }

    public function testMatch()
    {
        $router = new Router($this->routes);
        $this->assertArrayHasKey("name", $router->match("/user/foo"));
    }

    public function testMatchRequest()
    {
        $router = new Router($this->routes);
        $request = Request::create("/user/foo");
        $this->assertArrayHasKey("name", $router->matchRequest($request));
    }

    public function testGenerate()
    {
        $router = new Router($this->routes);
        $this->assertEquals("/user/bar", $router->generate("user", [ "name" => "bar" ]));
    }

    public function testGetContext()
    {
        $router = new Router($this->routes);
        $this->assertInstanceOf(RequestContext::class, $router->getContext());
    }

    public function testSetContext()
    {
        $router = new Router($this->routes);
        $this->assertEquals("/user/bar", $router->generate("user", [ "name" => "bar" ]));
        $router->setContext(new RequestContext("/subdir"));
        $this->assertEquals("/subdir/user/bar", $router->generate("user", [ "name" => "bar" ]));
    }
}
