<?php

declare(strict_types=1);

namespace App\Meeting\Application\Service;

use App\Meeting\Application\Query\FindMeetingQuery;
use App\Meeting\Domain\Aggregate\MeetingSerializer;
use App\Meeting\Infrastructure\Repository\MeetingQueryRepository;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Exception\ResourceNotFoundException;

final class MeetingFinderHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly MeetingQueryRepository $meetingRepository,
        private readonly MeetingSerializer $serializer,
    ) {
    }

    public function __invoke(FindMeetingQuery $findMeetingQuery): string
    {
        $meeting = $this->meetingRepository->find(
            $findMeetingQuery->getMeetingId()
        );

        if (null === $meeting) {
            throw new ResourceNotFoundException();
        }

        return json_encode($this->serializer->fromEntity($meeting), JSON_THROW_ON_ERROR);
    }
}
