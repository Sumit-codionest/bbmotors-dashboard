<?php

declare(strict_types=1);

namespace App\Core;

use App\Helpers\Response;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, callable|array $handler, array $middleware = []): void
    {
        $this->routes[] = compact('method', 'path', 'handler', 'middleware');
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            $pattern = '#^' . preg_replace('/\{([^}]+)\}/', '(?P<$1>[^/]+)', $route['path']) . '$#';
            if (preg_match($pattern, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                foreach ($route['middleware'] as $middlewareClass) {
                    (new $middlewareClass())->handle();
                }
                $handler = $route['handler'];
                if (is_array($handler)) {
                    [$class, $method] = $handler;
                    (new $class())->{$method}($params);
                    return;
                }
                $handler($params);
                return;
            }
        }
        Response::json(['message' => 'Not Found'], 404);
    }
}
