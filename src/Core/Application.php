<?php

/*
 * Monkey Boards.
 *
 * (c) Jamie Hurst <jamie@jamiehurst.co.uk>
 *
 * Copyright and licensing information available at
 * https://github.com/jamiefdhurst/monkey-boards
 */

namespace Monkey\Core;

use Symfony\Component\Dotenv\Dotenv;

/**
 * Application configuration container.
 */
class Application
{
    const CONFIG_DEFAULT = '.env.default';

    const CONTAINER_SERVICES = 'services.yaml';
    
    const PATH_RESOURCES = 'resources/';

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

    public function handleHttpRequest()
    {
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
        new Container(
            static::CONTAINER_SERVICES,
            $this->getPath().static::PATH_RESOURCES
        );
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
