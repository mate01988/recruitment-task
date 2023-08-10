<?php

declare(strict_types=1);

namespace App\Meeting\Domain\Factory;

use App\Meeting\Domain\Entity\Meeting;

final class MeetingFactory
{
    public function __construct()
    {
    }

    public function create(string $name, \DateTimeImmutable $startTime, int $maximumNumberOfParticipants): Meeting
    {
        return new Meeting($name, $startTime, $maximumNumberOfParticipants);
    }
}
