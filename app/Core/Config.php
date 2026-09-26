<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Simple config repository loading PHP arrays from config/*.php
 * with dot-notation access, e.g. config('database.default').
 */
final class Config
{
    /** @var array<string, mixed> */
    private static array $items = [];

    private static bool $loaded = false;

    public static function load(string $configDir): void
    {
        if (self::$loaded) {
            return;
        }
        self::$loaded = true;

        foreach (glob(rtrim($configDir, '/') . '/*.php') ?: [] as $file) {
            $key = basename($file, '.php');
            $data = require $file;
            if (is_array($data)) {
                self::$items[$key] = $data;
            }
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $value = self::$items;
        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }
        return $value;
    }

    /** @internal for tests */
    public static function reset(): void
    {
        self::$items = [];
        self::$loaded = false;
    }
}
