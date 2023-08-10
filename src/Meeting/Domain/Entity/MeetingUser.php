<?php

namespace App\Meeting\Domain\Entity;

use App\User\Domain\Entity\User;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`meetings_users`')]
#[ORM\Index(columns: ['rating'], name: 'rating_idx')]
class MeetingUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column]
    private readonly string $id;

    #[ORM\ManyToOne(targetEntity: Meeting::class, inversedBy: 'participants')]
    private Meeting $meeting;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'meetings')]
    private User $user;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $rating = null;

    public function __construct(Meeting $meeting, User $user)
    {
        $this->id = uniqid();
        $this->user = $user;
        $this->meeting = $meeting;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getMeeting(): Meeting
    {
        return $this->meeting;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }
}
