<?php

namespace App\Meeting\Infrastructure\Repository;

use App\Meeting\Domain\Entity\Meeting;
use App\Meeting\Domain\Entity\MeetingUser;
use Doctrine\ORM\EntityManagerInterface;

class MeetingQueryRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(string $meetingId): ?Meeting
    {
        return $this->entityManager->getRepository(Meeting::class)->find($meetingId);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function first(): ?Meeting
    {
        return $this->entityManager->createQueryBuilder()
            ->select('meeting')
            ->from(Meeting::class, 'meeting')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Meeting::class)->findAll();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findMeetingUser(string $meetingId, string $userId): ?MeetingUser
    {
        return $this->entityManager->createQueryBuilder()
            ->select('mu')
            ->from(MeetingUser::class, 'mu')
            ->where('mu.meeting = :meetingId')
            ->andWhere('mu.user = :userId')
            ->setParameter('meetingId', $meetingId)
            ->setParameter('userId', $userId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getAvgRating(string $meetingId): float
    {
        return $this->entityManager->createQueryBuilder()
            ->select('avg(mu.rating)')
            ->from(MeetingUser::class, 'mu')
            ->where('mu.meeting = :meetingId')
            ->setParameter('meetingId', $meetingId)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
