<?php

declare(strict_types=1);

namespace App\Middleware;

/**
 * Example auth gate. Replace the session check with real auth logic.
 */
class AuthMiddleware implements MiddlewareInterface
{
    public function handle(callable $next): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            echo view('errors/401', ['message' => 'Please log in to continue.']);
            return;
        }

        $next();
    }
}
