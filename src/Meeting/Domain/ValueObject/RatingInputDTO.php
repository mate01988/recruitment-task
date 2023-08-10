<?php

declare(strict_types=1);

namespace App\Meeting\Domain\ValueObject;

use App\Shared\Domain\ValueObject\OutputDTO;
use Symfony\Component\Validator\Constraints as Assert;

final class RatingInputDTO extends OutputDTO
{
    #[Assert\NotBlank]
    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: 'Rating must be between {{ min }} and {{ max }} to enter',
    )]
    public int $rating;

    #[Assert\NotBlank]
    public string $userId;
}
