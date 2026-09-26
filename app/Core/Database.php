<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

/**
 * PDO factory / singleton built from config/database.php.
 * Uses prepared statements at call sites (see Repositories).
 */
final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $default = (string) Config::get('database.default', 'mysql');
        $conn = Config::get('database.connections.' . $default);

        if (!is_array($conn)) {
            throw new \RuntimeException("Database connection [{$default}] is not configured.");
        }

        try {
            if (($conn['driver'] ?? 'mysql') === 'sqlite') {
                $pdo = new PDO('sqlite:' . $conn['database']);
            } else {
                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                    $conn['host'] ?? '127.0.0.1',
                    $conn['port'] ?? '3306',
                    $conn['database'] ?? '',
                    $conn['charset'] ?? 'utf8mb4'
                );
                $pdo = new PDO($dsn, $conn['username'] ?? '', $conn['password'] ?? '');
            }

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            self::$connection = $pdo;
            return $pdo;
        } catch (PDOException $e) {
            Logger::error('Database connection failed.');
            throw new \RuntimeException('Database connection failed.', 0, $e);
        }
    }

    /** @internal for tests */
    public static function reset(): void
    {
        self::$connection = null;
    }
}
