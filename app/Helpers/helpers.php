<?php

declare(strict_types=1);

use App\Core\Config;

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        $base = dirname(__DIR__, 2);
        return $path === '' ? $base : $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
        return $value === false || $value === null ? $default : $value;
    }
}

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        return Config::get($key, $default);
    }
}

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('view')) {
    function view(string $template, array $data = []): string
    {
        return \App\Core\View::render($template, $data);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url, int $status = 302): void
    {
        http_response_code($status);
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('abort')) {
    function abort(int $code, string $message = ''): void
    {
        http_response_code($code);
        echo \App\Core\View::render('errors/' . $code, ['message' => e($message)]);
        exit;
    }
}
