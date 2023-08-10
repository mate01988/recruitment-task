<?php

declare(strict_types=1);

namespace App\Meeting\Domain\Aggregate;

use App\Meeting\Domain\Entity\Meeting;
use App\Meeting\Domain\Entity\MeetingUser;
use App\Meeting\Domain\ValueObject\MeetingOutputDTO;
use App\User\Domain\Aggregate\UserSerializer;
use Symfony\Contracts\Translation\TranslatorInterface;

final class MeetingSerializer
{
    public const STATUS_TRANSLATE_PREFIX = 'meeting_status_%s';

    public function __construct(private readonly UserSerializer $userSerializer,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function fromEntity(Meeting $meeting): MeetingOutputDTO
    {
        $meetingOutputDTO = new MeetingOutputDTO();
        $meetingOutputDTO->id = $meeting->getId();
        $meetingOutputDTO->name = $meeting->getName();
        $meetingOutputDTO->startDate = $meeting->getStartTime()->format(DATE_RFC3339);
        $meetingOutputDTO->endDate = $meeting->getEndTime()->format(DATE_RFC3339);

        $meetingOutputDTO->status = $this->translator->trans(
            sprintf(self::STATUS_TRANSLATE_PREFIX, $meeting->getStatus()->name)
        );

        $meetingOutputDTO->users = array_map(function (MeetingUser $meetingUser) {
            return $this->userSerializer->fromEntity($meetingUser->getUser());
        }, $meeting->getParticipants()->toArray());

        return $meetingOutputDTO;
    }
}
