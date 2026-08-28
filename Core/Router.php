<?php

namespace Core;

class Router
{
    // Chaque route est stockée comme [pattern, handler] pour supporter les
    // segments dynamiques du type /biens/:id (pas seulement des chemins fixes)
    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][] = [$path, $handler];
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][] = [$path, $handler];
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $path = rtrim($path, '/') ?: '/';

        foreach ($this->routes[$method] ?? [] as [$pattern, $handler]) {
            $regex = preg_replace('#:[a-zA-Z_]+#', '([^/]+)', $pattern);
            if (preg_match('#^' . $regex . '$#', $path, $matches)) {
                array_shift($matches);
                [$controllerClass, $action] = $handler;
                $controller = new $controllerClass();
                $controller->$action(...$matches);
                return;
            }
        }

        http_response_code(404);
        echo "404 - Page non trouvée : {$method} {$path}";
    }
}
