<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract class OutputDTO
{
    public function toArray(): array
    {
        return json_decode(json_encode($this), true);
    }
}
