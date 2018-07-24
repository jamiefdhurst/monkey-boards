<?php

/**
 * Monkey Boards.
 *
 * (c) Jamie Hurst <jamie@jamiehurst.co.uk>
 *
 * Copyright and licensing information available at
 * https://github.com/jamiefdhurst/monkey-boards
 */

namespace Test\Core;

use Monkey\Core\Application;
use Monkey\Core\Route;
use Monkey\Core\Router;
use PHPUnit\Framework\MockObject\MockObject;
use Test\TestCase;
use Symfony\Component\HttpFoundation\Request;

class ApplicationTest extends TestCase
{
    /**
     * @var Route|MockObject
     */
    private $mockRoute;

    /**
     * @var Router|MockObject
     */
    private $mockRouter;

    /**
     * @var Application
     */
    private $sut;

    public function setUp()
    {
        $this->mockRoute = $this->createMock(Route::class);
        $this->mockRouter = $this->createMock(Router::class);

        $this->sut = new Application(null, $this->mockRouter);
    }

    public function testGetPath()
    {
        $expected = realpath(__DIR__.'/../..').'/';
        $this->assertEquals($expected, $this->sut->getPath());
    }

    public function testHandleHttpRequestCreatesRequestFromGlobals()
    {
        $this->mockRouter
            ->expects($this->once())
            ->method('match')
            ->willReturn($this->mockRoute);

        $this->mockRouter
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->mockRoute);

        $this->sut->handleHttpRequest();
    }

    public function testHandleHttpRequestUsesSuppliedRequest()
    {
        $request = Request::create('http://localhost/test');

        $this->mockRouter
            ->expects($this->once())
            ->method('match')
            ->with($request)
            ->willReturn($this->mockRoute);

        $this->mockRouter
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->mockRoute);

        $this->sut->handleHttpRequest($request);
    }
}
