<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Meeting;
use App\Entity\MeetingStatus;
use App\Exception\LimitParticipantsException;
use App\Factory\MeetingFactory;
use App\Factory\UserFactory;
use Behat\Behat\Context\Context;
use Symfony\Component\HttpKernel\KernelInterface;

final class MeetingContext implements Context
{
    private Meeting $meeting;

    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly MeetingFactory $meetingFactory,
        private readonly UserFactory $userFactory,
    ) {
    }

    /**
     * @Given There is a meeting in :arg1 minutes with :arg2 free space
     */
    public function thereIsAMeetingInMinutesWithFreeSpace(string $delay, int $limit): void
    {
        $this->meeting = $this->meetingFactory->create(
            'Daily',
            (new \DateTimeImmutable())->modify(sprintf('%s minutes', $delay)),
            $limit
        );
    }

    /**
     * @When I added :arg1 to meeting
     */
    public function iAddedParticipantsToMeeting(int $numberOfParticipants): void
    {
        for ($i = 0; $i < $numberOfParticipants; ++$i) {
            $this->meeting->addAParticipant(
                $this->userFactory->create(sprintf('User %d', $i))
            );
        }
    }

    /**
     * @Then I have :arg1 on meeting
     */
    public function iHaveParticipantsOnMeeting(int $numberOfParticipants): void
    {
        \PHPUnit\Framework\Assert::assertEquals(
            $numberOfParticipants,
            $this->meeting->getParticipants()->count()
        );
    }

    /**
     * @Then Status meeting is :arg1
     */
    public function statusMeetingIs(string $expectedStatus): void
    {
        \PHPUnit\Framework\Assert::assertEquals(
            MeetingStatus::tryFrom($expectedStatus)->value,
            $this->meeting->getStatus()->value,
        );
    }

    /**
     * @When I added :arg1 to meeting that should throw an exception
     */
    public function iAddedToMeetingThatShouldThrowAnException(int $numberOfParticipants): void
    {
        for ($i = 0; $i < $numberOfParticipants; ++$i) {
            try {
                $this->meeting->addAParticipant(
                    $this->userFactory->create(sprintf('User %d', $i))
                );
            } catch (LimitParticipantsException $exception) {
                \PHPUnit\Framework\Assert::assertEquals(
                    new LimitParticipantsException(),
                    $exception
                );
            }
        }
    }

    /**
     * @Then Then I should catch an exception
     */
    public function thenIShouldCatchAnException(): void
    {
        if (!$this->kernel->getContainer()->get('kernel')->isDebug()) {
            throw new \Exception('Expected exception was not thrown.');
        }
    }
}
