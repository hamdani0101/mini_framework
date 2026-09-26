<?php

declare(strict_types=1);

use App\Controllers\HomeController;

/** @var \App\Core\Router $router */

// Public routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/health', [HomeController::class, 'health']);

// Example protected group (uncomment when auth exists):
// $router->group(
//     [\App\Middleware\AuthMiddleware::class],
//     function (\App\Core\Router $router): void {
//         $router->get('/dashboard', [HomeController::class, 'index']);
//     }
// );
