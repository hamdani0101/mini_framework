<?php

declare(strict_types=1);

/**
 * Example seeder (opt-in). Run manually, e.g.:
 *   php -r "require 'vendor/autoload.php'; ..."
 */

return new class {
    public function run(PDO $pdo): void
    {
        $stmt = $pdo->prepare('INSERT IGNORE INTO users (name, email) VALUES (:name, :email)');
        $stmt->execute([':name' => 'Example User', ':email' => 'user@example.com']);
    }
};
