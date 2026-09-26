<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\User;
use PDO;

/**
 * All SQL for users lives here. Always prepared statements, never in views.
 */
class UserRepository
{
    public function __construct(private ?PDO $pdo = null)
    {
    }

    private function pdo(): PDO
    {
        return $this->pdo ?? Database::connection();
    }

    /** @return User[] */
    public function all(int $limit = 100): array
    {
        $stmt = $this->pdo()->prepare('SELECT id, name, email FROM users ORDER BY id DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return array_map(
            static fn(array $row) => User::fromRow($row),
            $stmt->fetchAll()
        );
    }

    public function find(int $id): ?User
    {
        $stmt = $this->pdo()->prepare('SELECT id, name, email FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row === false ? null : User::fromRow($row);
    }
}
