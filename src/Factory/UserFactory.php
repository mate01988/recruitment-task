<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;

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
