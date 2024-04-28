<?php

namespace Jauhar\System;

use Exception;
use Jauhar\System\Helpers\ReflectionHelper;
use Jauhar\System\Interface\RouteModifier;
use Jauhar\System\Routes\Route;
use ReflectionAttribute;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing;
use Symfony\Component\HttpFoundation\Response;

class App
{

    /**
     * @var Module[]
     */
    protected $modules = [];

    public function registerModule(Module $module)
    {
        $this->modules[] = $module;
    }

    /**
     * @return Module[]
     */
    public function getModules()
    {
        return $this->modules;
    }

    public function run()
    {
        $request = Request::createFromGlobals();
        $routes = $this->getRoutes();
        $context = new Routing\RequestContext();
        $context->fromRequest($request);
        $matcher = new Routing\Matcher\UrlMatcher($routes, $context);

        try {
            $result = $matcher->match($request->getPathInfo());

            ob_start();
            $this->dispatch($result);

            $response = new Response(ob_get_clean());
        } catch (Routing\Exception\ResourceNotFoundException $exception) {
            $response = new Response("Route Not Found : {$request->getPathInfo()}", 404);
        } catch (Routing\Exception\MethodNotAllowedException $exception) {
            $response = new Response('Method Not Allowed', 405);
        } catch (Exception $exception) {
            $response = new Response('An error occurred ' . $exception, 500);
        }

        $response->send();
    }

    public function getRoutes(): Routing\RouteCollection
    {
        $routes = new Routing\RouteCollection();
        $modules = $this->getModules();

        foreach ($modules as $module) {
            $controllers = $module->getControllers();
            foreach ($controllers as $controller) {
                $controllerRoutes = $this->getRoutesFromControllers($controller);
                foreach ($controllerRoutes as $route) {
                    $routes->add(
                        $route['name'],
                        new Routing\Route(
                            $route['path'],
                            defaults: [
                                '_context' => [
                                    'route' => $route
                                ]
                            ],
                            methods: [$route['method']]
                        )
                    );
                }
            }
        }
        return $routes;
    }

    public function getRoutesFromControllers(string $controller)
    {
        $routes = [];

        $reflection = new ReflectionClass($controller);
        $routeModifiers = ReflectionHelper::getAttributInstance(
            $reflection,
            RouteModifier::class,
            ReflectionAttribute::IS_INSTANCEOF
        );
        $methods = $reflection->getMethods();
        foreach ($methods as $method) {
            $attributes = $method->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF);
            if (count($attributes) > 0) {
                $route =  $attributes[0]->newInstance();
                $route = $this->applyModifiers($route, $routeModifiers);


                $routes[] = [
                    'handler' => [$controller, $method->getName()],
                    'method' => $route->getMethod(),
                    'path' => $route->getPath(),
                    'name' => $route->getName()
                ];
            }
        }
        return $routes;
    }

    public function applyModifiers(Route $route, array $modifiers): Route
    {
        foreach ($modifiers as $modifier) {
            $route = $modifier->modifyRoute($route);
        }

        return $route;
    }

    public function dispatch(array $result)
    {
        [$controller, $method] = $result['_context']['route']['handler'];
        $ctrl  = new $controller;
        $args = $this->resolveArgs($result);
        // var_dump($args);
        call_user_func_array([$ctrl, $method], $args);
    }

    public function resolveArgs(array $result): array
    {
        $args = [];

        foreach ($result as $key => $res) {
            if (!str_starts_with($key, '_')) {
                $args[$key] = $res;
            }
        }
        return $args;
    }
}
