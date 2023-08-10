<?php

namespace App\Meeting\Domain\Entity;

use App\Shared\Domain\Exception\LimitParticipantsException;
use App\User\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`meetings`')]
class Meeting
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column]
    public readonly string $id;

    #[ORM\Column(length: 255)]
    public readonly string $name;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public readonly \DateTimeImmutable $startTime;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public readonly \DateTimeImmutable $endTime;

    #[ORM\ManyToMany(targetEntity: User::class)]
    public Collection $participants;

    public readonly int $maxUsers;

    public function __construct(string $name, \DateTimeImmutable $startTime, int $maxUsers)
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->participants = new ArrayCollection();
        $this->maxUsers = $maxUsers;
        $this->startTime = $startTime;
        $this->endTime = $startTime->add(\DateInterval::createFromDateString('1 hour'));
    }

    /**
     * @throws LimitParticipantsException
     */
    public function addAParticipant(User $participant): void
    {
        if ($this->getParticipants()->count() >= $this->maxUsers) {
            throw new LimitParticipantsException();
        }

        $this->participants->add($participant);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMaxUsers(): int
    {
        return $this->maxUsers;
    }

    public function getStatus(): MeetingStatus
    {
        if ($this->getEndTime()->getTimestamp() < time()) {
            return MeetingStatus::Done;
        } elseif ($this->getParticipants()->count() >= $this->maxUsers) {
            return MeetingStatus::Full;
        } elseif ($this->getStartTime()->getTimestamp() <= time()) {
            return MeetingStatus::InSession;
        }

        return MeetingStatus::Open;
    }

    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function getEndTime(): \DateTimeImmutable
    {
        return $this->endTime;
    }

    public function getStartTime(): \DateTimeImmutable
    {
        return $this->startTime;
    }
}
