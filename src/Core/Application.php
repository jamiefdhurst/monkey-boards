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

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Monkey\Core\Exception\ControllerNotFoundException;

/**
 * Application configuration container.
 */
class Application
{
    const CONFIG_DEFAULT = '.env.default';
    const CONTAINER_SERVICES = 'services.yaml';
    const PATH_RESOURCES = 'resources/';

    /**
     * @var Container
     */
    private $container;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Application
     */
    private static $instance;

    /**
     * Set up required application configuration.
     */
    public function __construct()
    {
        $this->loadConfiguration();
        $this->loadContainer();
        $this->loadRouter();
    }

    /**
     * Get application base path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return __DIR__.'/../../';
    }

    /**
     * Handle a given HTTP request, dispatching the router and running the
     * controller to generate and send a response.
     *
     * @param Request $request
     */
    public function handleHttpRequest(Request $request = null)
    {
        if (null === $request) {
            $request = Request::createFromGlobals();
        }

        $route = $this->router->match();
        $this->router->dispatch($route);
    }

    /**
     * Load configuration into environment variables.
     */
    private function loadConfiguration()
    {
        $config = Dotenv::load(
            $this->getPath().static::PATH_RESOURCES.static::CONFIG_DEFAULT
        );
    }

    /**
     * Load the container using the standard services file.
     */
    private function loadContainer()
    {
        $this->container = new Container(
            static::CONTAINER_SERVICES,
            $this->getPath().static::PATH_RESOURCES
        );
    }
    
    /**
     * Load the router using the container, and initialise it.
     */
    private function loadRouter()
    {
        $this->router = $this->container->get('core.router');
        $this->router->load();
    }

    /**
     * Return singleton instance of application.
     *
     * @return Application
     */
    public static function getInstance(): self
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}
