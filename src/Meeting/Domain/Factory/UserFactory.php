<?php

declare(strict_types=1);

namespace App\Meeting\Domain\Factory;

use App\User\Domain\Entity\User;

class UserFactory
{
    public function __construct()
    {
    }

    public function create(string $name): User
    {
        return new User($name);
    }
}
