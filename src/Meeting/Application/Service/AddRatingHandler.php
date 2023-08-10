<?php

declare(strict_types=1);

namespace App\Meeting\Application\Service;

use App\Meeting\Application\Command\AddRatingCommand;
use App\Meeting\Domain\Entity\MeetingStatus;
use App\Meeting\Infrastructure\Repository\MeetingCommandRepository;
use App\Meeting\Infrastructure\Repository\MeetingQueryRepository;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\AlreadyExistsException;
use App\Shared\Domain\Exception\InvalidStatusException;
use App\Shared\Domain\Exception\ResourceNotFoundException;

final class AddRatingHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly MeetingQueryRepository $meetingQueryRepository,
        private readonly MeetingCommandRepository $meetingCommandRepository,
    ) {
    }

    public function __invoke(AddRatingCommand $addRatingCommand): void
    {
        $meetingUser = $this->meetingQueryRepository->findMeetingUser(
            $addRatingCommand->getMeetingId(),
            $addRatingCommand->getRatingInputDTO()->userId,
        );

        if (null === $meetingUser) {
            throw new ResourceNotFoundException();
        }

        if (null !== $meetingUser->getRating()) {
            throw new AlreadyExistsException();
        }

        if (MeetingStatus::Done !== $meetingUser->getMeeting()->getStatus()) {
            throw new InvalidStatusException();
        }

        $this->meetingCommandRepository->updateRating($meetingUser, $addRatingCommand->getRatingInputDTO()->rating);
    }
}
