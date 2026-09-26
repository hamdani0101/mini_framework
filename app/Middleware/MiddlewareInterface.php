<?php

declare(strict_types=1);

namespace App\Middleware;

/**
 * Middleware contract. Call $next() to continue the pipeline.
 */
interface MiddlewareInterface
{
    public function handle(callable $next): void;
}
