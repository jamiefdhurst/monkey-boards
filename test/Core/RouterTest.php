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

use Monkey\Core\Route;
use Monkey\Core\Router;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Test\TestCase;
use Monkey\Core\Exception\ControllerNotFoundException;
use Monkey\Core\Exception\NotFoundException;
use Monkey\Core\Exception\PermissionException;

class RouterTest extends TestCase
{
    const ROUTES_PATH = __DIR__ . '/../../resources/';
    const ROUTES_FILE = 'routes.yaml';

    /**
     * @var Router
     */
    private $sut;

    public function setUp()
    {
        $this->sut = new Router();
        $this->sut->load(self::ROUTES_PATH, self::ROUTES_FILE);
    }

    public function testDispatch()
    {
        $route = $this->createMock(Route::class);
        $route->expects($this->once())
            ->method('run');

        $this->sut->dispatch($route);
    }

    public function testDispatchHandlesControllerException()
    {
        $route = $this->createMock(Route::class);
        $route->expects($this->once())
            ->method('run')
            ->willThrowException(new ControllerNotFoundException(''));

        $this->sut->dispatch($route);
    }

    public function testDispatchHandlesNotFoundException()
    {
        $route = $this->createMock(Route::class);
        $route->expects($this->once())
            ->method('run')
            ->willThrowException(new NotFoundException(''));

        $this->sut->dispatch($route);
    }

    public function testDispatchHandlesPermissionException()
    {
        $route = $this->createMock(Route::class);
        $route->expects($this->once())
            ->method('run')
            ->willThrowException(new PermissionException(''));

        $this->sut->dispatch($route);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testLoadException()
    {
        $this->sut->load(
            __DIR__ . '../non-existent-folder',
            self::ROUTES_FILE
        );
    }

    public function testMatch()
    {
        $request = Request::create('/');
        $route = $this->sut->match($request);

        $this->assertInstanceOf(Route::class, $route);
    }

    /**
     * @expectedException Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function testMatchThrowsException()
    {
        $request = Request::create('/definitely/not/a/real/route');
        $route = $this->sut->match($request);

        $this->assertInstanceOf(Route::class, $route);
    }
}
