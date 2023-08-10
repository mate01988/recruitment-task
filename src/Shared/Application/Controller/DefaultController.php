<?php

declare(strict_types=1);

namespace App\Shared\Application\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DefaultController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return new Response('<h1>Hello</h1>');
    }
}
