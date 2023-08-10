<?php

namespace App\User\Domain\Entity;

use App\Meeting\Domain\Entity\MeetingUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`users`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(length: 13)]
    private readonly string $id;

    #[ORM\Column(length: 128, nullable: true)]
    private readonly string $name;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: MeetingUser::class, cascade: ['persist'])]
    private Collection $meetings;

    public function __construct(string $name)
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->meetings = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMeetings(): Collection
    {
        return $this->meetings;
    }
}
