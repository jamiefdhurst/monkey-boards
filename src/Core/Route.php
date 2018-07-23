<?php

/**
 * Monkey Boards.
 *
 * (c) Jamie Hurst <jamie@jamiehurst.co.uk>
 *
 * Copyright and licensing information available at
 * https://github.com/jamiefdhurst/monkey-boards
 */

namespace Monkey\Core;

use Symfony\Component\HttpFoundation\Request;
use Monkey\Core\Exception\ControllerNotFoundException;
use Monkey\Core\Exception\NotFoundException;

/**
 * Route from request that contains controller information and can resolve and
 * be dispatched to that controller.
 */
class Route
{
    /**
     * @var string
     */
    private $controller;

    /**
     * @var array
     */
    private $middleware = [];

    /**
     * @var array
     */
    private $permissions = ['*'];

    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request $request
     * @param array $opts
     */
    public function __construct(Request $request, array $opts = [])
    {
        $this->request = $request;
        if (array_key_exists('_controller', $opts)) {
            $this->controller = $opts['_controller'];
        }
        if (array_key_exists('_middleware', $opts)) {
            $this->middleware = $opts['_middleware'];
        }
        if (array_key_exists('_permissions', $opts)) {
            $this->permissions = $opts['_permissions'];
        }
    }

    /**
     * Get the middleware for this route.
     *
     * @return array
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    /**
     * Get the permissions for this route.
     *
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions();
    }

    /**
     * Get the request that originated this route.
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Run the controller and send the final response.
     *
     * @throws ControllerNotFoundException
     * @throws NotFoundException
     */
    public function run()
    {
        if (!$this->controller) {
            throw new NotFoundException($request);
        }

        list($class, $action) = explode('::', $this->controller);
        if (!class_exists($class)) {
            throw new ControllerNotFoundException($class);
        }

        $controller = new $class($this);
        $controller->beforeAction();
        $response = $controller->$action();
        $controller->afterAction($response);
        $response->send();
    }
}
