<?php

declare(strict_types=1);

namespace App\User\Domain\Aggregate;

use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserOutputDTO;

final class UserSerializer
{
    public function fromEntity(User $user): UserOutputDTO
    {
        $userOutputDTO = new UserOutputDTO();
        $userOutputDTO->id = $user->getId();
        $userOutputDTO->name = $user->getName();

        return $userOutputDTO;
    }
}
