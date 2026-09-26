<?php

declare(strict_types=1);

namespace App\Core;

/**
 * File logger writing to storage/logs. Never echoes sensitive values.
 */
final class Logger
{
    public static function info(string $message, array $context = []): void
    {
        self::write('INFO', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::write('ERROR', $message, $context);
    }

    private static function write(string $level, string $message, array $context): void
    {
        try {
            $dir = base_path('storage/logs');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $file = $dir . '/' . date('Y-m-d') . '.log';
            $line = sprintf(
                "[%s] %s: %s %s\n",
                date('Y-m-d H:i:s'),
                $level,
                $message,
                $context !== [] ? json_encode($context) : ''
            );
            error_log($line, 3, $file);
        } catch (\Throwable) {
            // Logging must never break the request.
        }
    }
}
