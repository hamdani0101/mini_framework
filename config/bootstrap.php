<?php

declare(strict_types=1);

/**
 * Application bootstrap: autoload, .env, config, sessions, error handling.
 * Included by public/index.php (the only web entry point).
 */

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Core\Config;
use App\Core\Env;
use App\Core\Logger;

Env::load(dirname(__DIR__) . '/.env');
Config::load(dirname(__DIR__) . '/config');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);

$debug = filter_var(Config::get('app.debug', false), FILTER_VALIDATE_BOOLEAN);

if (!$debug) {
    ini_set('display_errors', '0');
    set_exception_handler(function (\Throwable $e): void {
        Logger::error('Unhandled exception.', ['message' => $e->getMessage()]);
        http_response_code(500);
        echo 'Something went wrong.';
    });
}
