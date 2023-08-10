<?php

declare(strict_types=1);

namespace App\Meeting\Domain\ValueObject;

use App\Shared\Domain\ValueObject\OutputDTO;

final class MeetingOutputDTO extends OutputDTO
{
    public string $id;
    public string $name;
    public string $startDate;
    public string $endDate;
    public string $status;
    /**
     * @var \App\User\Domain\ValueObject\UserOutputDTO[]|array
     */
    public array $users;
}
