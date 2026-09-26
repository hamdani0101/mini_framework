<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

/**
 * Base controller. Keep thin: HTTP glue only.
 * Business logic belongs in Services, data access in Repositories.
 */
class Controller
{
    protected function render(string $template, array $data = [], int $status = 200): void
    {
        http_response_code($status);
        echo View::render($template, $data);
    }

    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    protected function redirect(string $url, int $status = 302): void
    {
        redirect($url, $status);
    }

    protected function input(string $key, mixed $default = null): mixed
    {
        return $_REQUEST[$key] ?? $default;
    }
}
