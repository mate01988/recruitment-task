<?php

declare(strict_types=1);

namespace App\Meeting\Application\Command;

use App\Meeting\Domain\ValueObject\RatingInputDTO;

final class AddRatingCommand
{
    private string $meetingId;
    private RatingInputDTO $ratingInputDTO;

    public function __construct(string $meetingId, RatingInputDTO $ratingInputDTO)
    {
        $this->meetingId = $meetingId;
        $this->ratingInputDTO = $ratingInputDTO;
    }

    public function getMeetingId(): string
    {
        return $this->meetingId;
    }

    public function getRatingInputDTO(): RatingInputDTO
    {
        return $this->ratingInputDTO;
    }
}
