<?php

namespace App\Meeting\Infrastructure\Repository;

use App\Meeting\Domain\Entity\Meeting;
use App\Meeting\Domain\Entity\MeetingUser;
use Doctrine\ORM\EntityManagerInterface;

class MeetingCommandRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(Meeting $newMeeting): void
    {
        $this->entityManager->persist($newMeeting);
        $this->entityManager->flush();
    }

    public function updateRating(MeetingUser $meetingUser, int $rating): void
    {
        $meetingUser->setRating($rating);

        $this->entityManager->persist($meetingUser);
        $this->entityManager->flush();
    }
}
