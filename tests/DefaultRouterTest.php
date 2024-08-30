<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Sigsign\IceMint\Router;
use Symfony\Component\HttpFoundation\Request;

class DefaultRouterTest extends TestCase
{
    public function testMatch(): void
    {
        $router = new Router();
        $this->assertArrayHasKey('_controller', $router->match('/'));
    }

    public function testMatchRequest(): void
    {
        $router = new Router();
        $request = Request::create('/');
        $this->assertArrayHasKey('_controller', $router->matchRequest($request));
    }

    public function testGenerate(): void
    {
        $router = new Router();
        $this->assertEquals('/', $router->generate('top'));
    }
}
