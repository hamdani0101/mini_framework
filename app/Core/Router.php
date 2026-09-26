<?php

declare(strict_types=1);

namespace App\Core;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

/**
 * FastRoute wrapper. Routes are registered in routes/web.php via
 * $router->get('/path', [Controller::class, 'method']).
 */
final class Router
{
    /** @var array<int, array{method: string, path: string, handler: mixed, middleware: array<int, string>}> */
    private array $routes = [];

    /** @var array<int, string> */
    private array $groupMiddleware = [];

    public function get(string $path, mixed $handler, array $middleware = []): void
    {
        $this->add('GET', $path, $handler, $middleware);
    }

    public function post(string $path, mixed $handler, array $middleware = []): void
    {
        $this->add('POST', $path, $handler, $middleware);
    }

    public function add(string $method, string $path, mixed $handler, array $middleware = []): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'middleware' => [...$this->groupMiddleware, ...$middleware],
        ];
    }

    /**
     * @param array<int, string> $middleware
     * @param callable(Router): void $callback
     */
    public function group(array $middleware, callable $callback): void
    {
        $previous = $this->groupMiddleware;
        $this->groupMiddleware = [...$previous, ...$middleware];
        $callback($this);
        $this->groupMiddleware = $previous;
    }

    public function dispatch(string $httpMethod, string $uri): void
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $r): void {
            foreach ($this->routes as $route) {
                $r->addRoute($route['method'], $route['path'], $route);
            }
        });

        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $routeInfo = $dispatcher->dispatch($httpMethod, $path);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                http_response_code(404);
                echo View::render('errors/404', ['path' => e($path)]);
                return;

            case Dispatcher::METHOD_NOT_ALLOWED:
                http_response_code(405);
                echo View::render('errors/405', ['path' => e($path)]);
                return;

            case Dispatcher::FOUND:
                /** @var array{handler: mixed, middleware: array<int, string>} $route */
                $route = $routeInfo[1];
                $vars = $routeInfo[2];
                $this->runMiddlewareStack($route['middleware'], $route['handler'], $vars);
                return;
        }
    }

    /**
     * @param array<int, string> $middleware
     */
    private function runMiddlewareStack(array $middleware, mixed $handler, array $vars): void
    {
        $runner = function (int $index) use (&$runner, $middleware, $handler, $vars): void {
            if (isset($middleware[$index])) {
                $class = $middleware[$index];
                $instance = new $class();
                $instance->handle(fn() => $runner($index + 1));
                return;
            }
            $this->callHandler($handler, $vars);
        };

        $runner(0);
    }

    private function callHandler(mixed $handler, array $vars): void
    {
        try {
            if (is_array($handler) && count($handler) === 2) {
                [$class, $method] = $handler;
                $controller = new $class();
                $controller->$method(...array_values($vars));
                return;
            }

            if (is_callable($handler)) {
                $handler(...array_values($vars));
                return;
            }

            throw new \RuntimeException('Invalid route handler.');
        } catch (\Throwable $e) {
            Logger::error('Unhandled exception.', ['message' => $e->getMessage()]);
            http_response_code(500);
            if (Config::get('app.debug', false)) {
                echo View::render('errors/500', ['message' => e($e->getMessage())]);
            } else {
                echo View::render('errors/500', ['message' => 'Something went wrong.']);
            }
        }
    }
}
