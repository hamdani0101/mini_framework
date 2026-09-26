<?php

declare(strict_types=1);

/**
 * Front controller. This file is the ONLY PHP entry point reachable from the web.
 * Document root must be public/.
 */

require dirname(__DIR__) . '/config/bootstrap.php';

use App\Core\Router;

$router = new Router();

require dirname(__DIR__) . '/routes/web.php';

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
