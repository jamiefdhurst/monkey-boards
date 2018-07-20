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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Wrap Symfony DependencyInjection.
 */
class Container
{
    /**
     * Symofny container instance.
     *
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var Container
     */
    private static $instance;

    /**
     * Load provided services and pre-compile container.
     *
     * @param string $servicesFile
     * @param string $basePath
     */
    public function __construct(string $servicesFile, string $servicesPath)
    {
        $this->container = new ContainerBuilder();
        $loader = new YamlFileLoader(
            $this->container,
            new FileLocator($servicesPath)
        );
        $loader->load($servicesFile);
        $this->container->compile();

        static::$instance = $this;
    }

    /**
     * Call the container's get function.
     *
     * @param string $type
     *
     * @return mixed
     */
    public function get(string $id)
    {
        return $this->container->get($id);
    }

    /**
     * Return singleton instance of container.
     *
     * @return Container
     */
    public static function getInstance(): self
    {
        return static::$instance;
    }
}
