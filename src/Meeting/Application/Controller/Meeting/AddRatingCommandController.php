<?php

declare(strict_types=1);

namespace App\Meeting\Application\Controller\Meeting;

use App\Meeting\Application\Command\AddRatingCommand;
use App\Meeting\Domain\ValueObject\RatingInputDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/meeting/{id}/rating', name: 'api_post_meeting_rating', methods: ['PATCH'])]
final class AddRatingCommandController extends AbstractController
{
    use HandleTrait;

    private ValidatorInterface $validator;

    public function __construct(MessageBusInterface $commandBus,
        ValidatorInterface $validator,
    ) {
        $this->messageBus = $commandBus;
        $this->validator = $validator;
    }

    public function __invoke(string $id, Request $request): Response
    {
        // todo: this version is for demo purposes only
        $parameters = json_decode(
            $request->getContent(),
            true, 512,
            JSON_THROW_ON_ERROR
        );

        $ratingInputDTO = new RatingInputDTO();
        $ratingInputDTO->rating = (int) $parameters['rating'];
        $ratingInputDTO->userId = $parameters['userId'];

        $errors = $this->validator->validate($ratingInputDTO);

        if (count($errors) > 0) {
            return new JsonResponse(['errorMessage' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->handle(new AddRatingCommand(
                $id,
                $ratingInputDTO,
            ));
        } catch (\Throwable $throwable) {
            return JsonResponse::fromJsonString('{}', Response::HTTP_BAD_REQUEST);
        }

        return new Response(null, Response::HTTP_OK);
    }
}
