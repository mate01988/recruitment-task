<?php

declare(strict_types=1);

namespace App\Meeting\Application\Controller\Meeting;

use App\Meeting\Application\Query\FindMeetingQuery;
use App\Shared\Domain\Exception\ResourceNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/meeting/{id}', name: 'api_get_meeting', methods: ['GET'])]
final class GetMeetingQueryController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function __invoke(string $id): JsonResponse
    {
        $meetingResponse = '{}';
        try {
            $meetingResponse = $this->handle(new FindMeetingQuery($id));
        } catch (\Throwable $throwable) {
            if ($throwable->getPrevious() instanceof ResourceNotFoundException) {
                return JsonResponse::fromJsonString($meetingResponse, Response::HTTP_NOT_FOUND);
            }
        }

        return JsonResponse::fromJsonString($meetingResponse);
    }
}
