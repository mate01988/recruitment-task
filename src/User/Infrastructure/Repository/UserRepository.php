<?php

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function find(string $userId): ?User
    {
        return $this->entityManager->getRepository(User::class)->find($userId);
    }

    public function first(): ?User
    {
        return $this->entityManager->createQueryBuilder()->select('user')
            ->from(User::class, 'user')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(User::class)->findAll();
    }
}
