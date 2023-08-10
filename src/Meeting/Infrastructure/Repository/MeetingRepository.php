<?php

namespace App\Meeting\Infrastructure\Repository;

use App\Meeting\Domain\Entity\Meeting;
use Doctrine\ORM\EntityManagerInterface;

class MeetingRepository
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

    public function find(string $meetingId): ?Meeting
    {
        return $this->entityManager->getRepository(Meeting::class)->find($meetingId);
    }

    public function first(): ?Meeting
    {
        return $this->entityManager->createQueryBuilder()->select('meeting')
            ->from(Meeting::class, 'meeting')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Meeting::class)->findAll();
    }
}
