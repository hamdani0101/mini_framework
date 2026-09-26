<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Example domain object. Table creation (if needed) lives in database/migrations.
 */
class User extends Model
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public string $email = ''
    ) {
    }

    public static function fromRow(array $row): self
    {
        return new self(
            id: isset($row['id']) ? (int) $row['id'] : null,
            name: (string) ($row['name'] ?? ''),
            email: (string) ($row['email'] ?? '')
        );
    }
}
