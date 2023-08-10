<?php

namespace App\Tests\Behat;

use App\Meeting\Domain\Entity\Meeting;
use App\Meeting\Domain\Entity\MeetingUser;
use App\Meeting\Domain\Factory\MeetingFactory;
use App\Meeting\Domain\Factory\UserFactory;
use App\Meeting\Infrastructure\Repository\MeetingCommandRepository;
use App\Meeting\Infrastructure\Repository\MeetingQueryRepository;
use App\User\Infrastructure\Repository\UserRepository;
use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class RatingContext implements Context
{
    private Meeting $meeting;

    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly MeetingFactory $meetingFactory,
        private readonly UserFactory $userFactory,
        private readonly MeetingCommandRepository $meetingCommandRepository,
        private readonly MeetingQueryRepository $meetingQueryRepository,
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * @Given There is a closed meeting with :arg1 participant
     */
    public function thereIsAClosedMeetingWithParticipants(int $number)
    {
        $this->meeting = $this->meetingFactory->create(
            'Daily',
            (new \DateTimeImmutable())->modify('-2 hours'),
            $number
        );

        for ($i = 0; $i < $number; ++$i) {
            $user = $this->userFactory->create(sprintf('User %d', $i));
            $this->userRepository->add($user);

            $this->meeting->addAParticipant($user);
        }

        $this->meetingCommandRepository->add($this->meeting);
    }

    /**
     * @When I added :arg1 to meeting for each participant
     */
    public function iAddedToMeetingForEachParticipants(int $rating)
    {
        /**
         * @var MeetingUser $meetingUser
         */
        foreach ($this->meeting->getParticipants() as $k => $meetingUser) {
            $response = $this->kernel->handle(
                Request::create(
                    uri: sprintf('/api/meeting/%s/rating', $this->meeting->getId()),
                    method: Request::METHOD_PATCH,
                    server: ['CONTENT_TYPE' => 'application/json; charset=utf-8'],
                    content: json_encode([
                        'userId' => $meetingUser->getUser()->getId(),
                        'rating' => $rating,
                    ])
                )
            );

            if (Response::HTTP_OK != $response->getStatusCode()) {
                throw new \Exception('invalid status code response');
            }
        }
    }

    /**
     * @Then I have the :arg1 of the rating for that meeting
     */
    public function iHaveTheOfTheRatingForThatMeeting(float $avg)
    {
        $calcAvg = $this->meetingQueryRepository->getAvgRating($this->meeting->getId());
        if ($calcAvg !== $avg) {
            throw new \Exception('invalid avg value');
        }
    }

    /**
     * @When I added invalid :arg1 to meeting for each participant
     */
    public function iAddedInvalidToMeetingForEachParticipants(int $rating)
    {
        /**
         * @var MeetingUser $meetingUser
         */
        foreach ($this->meeting->getParticipants() as $k => $meetingUser) {
            $response = $this->kernel->handle(
                Request::create(
                    uri: sprintf('/api/meeting/%s/rating', $this->meeting->getId()),
                    method: Request::METHOD_PATCH,
                    server: ['CONTENT_TYPE' => 'application/json; charset=utf-8'],
                    content: json_encode([
                        'userId' => $meetingUser->getUser()->getId(),
                        'rating' => $rating,
                    ])
                )
            );

            if (Response::HTTP_BAD_REQUEST != $response->getStatusCode()) {
                throw new \Exception('invalid status code response');
            }
        }
    }

    /**
     * @Given There is an unclosed meeting with :arg1 participant
     */
    public function thereIsAnUnclosedMeetingInMinutesParticipant(int $number)
    {
        $this->meeting = $this->meetingFactory->create(
            'Daily',
            (new \DateTimeImmutable())->modify('+2 hours'),
            $number
        );
    }
}
