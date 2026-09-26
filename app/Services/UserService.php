<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

/**
 * Business logic layer. Controllers call services, services call repositories.
 */
class UserService
{
    public function __construct(private UserRepository $users = new UserRepository())
    {
    }

    /** @return User[] */
    public function listUsers(int $limit = 100): array
    {
        return $this->users->all(max(1, min($limit, 500)));
    }
}
