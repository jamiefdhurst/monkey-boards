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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Monkey\Core\Exception\ControllerNotFoundException;
use Monkey\Core\Exception\NotFoundException;
use Monkey\Core\Exception\PermissionException;
use Monkey\Error\ErrorController;

/**
 * Takes a request and matches against a set of routes, passing the given
 * information back to the callee.
 */
class Router
{
    /**
     * @var RouteCollection
     */
    private $routes;

    /**
     * Dispatch a route, handling an error if one occurs.
     *
     * @param Route $route
     */
    public function dispatch(Route $route)
    {
        try {
            $route->run();
        } catch (ControllerNotFoundException $e) {
            $errorRoute = new Route($route->getRequest(), [
                '_controller' => ErrorController::class.'::systemError'
            ]);
            $errorRoute->run();
        } catch (PermissionException $e) {
            $errorRoute = new Route($route->getRequest(), [
                '_controller' => ErrorController::class.'::permissionError'
            ]);
            $errorRoute->run();
        } catch (NotFoundException $e) {
            $errorRoute = new Route($route->getRequest(), [
                '_controller' => ErrorController::class.'::notFoundError'
            ]);
            $errorRoute->run();
        }
    }

    /**
     * Load routes, preparing for a match.
     *
     * @param string $routesPath
     * @param string $routesFile
     */
    public function load(string $routesPath, string $routesFile)
    {
        $loader = new YamlFileLoader(
            new FileLocator($routesPath)
        );
        $this->routes = $loader->load($routesFile);
    }

    /**
     * Match against a request, producing a route.
     *
     * @param Request $request
     * @return Route
     */
    public function match(Request $request)
    {
        $context = new RequestContext();
        $context->fromRequest($request);

        $matcher = new UrlMatcher($this->routes, $context);
        return new Route($request, $matcher->matchRequest($request));
    }
}
