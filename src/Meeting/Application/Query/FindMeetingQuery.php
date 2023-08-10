<?php

declare(strict_types=1);

namespace App\Meeting\Application\Query;

final class FindMeetingQuery
{
    private string $meetingId;

    public function __construct(string $meetingId)
    {
        $this->meetingId = $meetingId;
    }

    public function getMeetingId(): string
    {
        return $this->meetingId;
    }
}
