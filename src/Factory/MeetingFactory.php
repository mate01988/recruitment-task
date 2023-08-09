<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Meeting;

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
