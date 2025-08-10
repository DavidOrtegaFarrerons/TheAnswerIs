<?php

namespace App\Controller;

use App\Dto\CreateGameDto;
use App\Service\CreateGameService;
use App\ValueResolver\CreateGameDtoResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{

    const GAME_CREATE = 'game_create';

    public function __construct(private readonly CreateGameService $gameCreationService)
    {
    }

    #[Route('/game/create', name: self::GAME_CREATE, methods: ['POST'])]
    public function createAction(
        #[ValueResolver(CreateGameDtoResolver::TARGETED_VALUE_RESOLVER_NAME)]
        CreateGameDto $dto
    ): RedirectResponse
    {
        $game = $this->gameCreationService->create($dto);

        return $this->redirectToRoute(GamePresenterController::GAME_PRESENTER_LOBBY_ROUTE_NAME, ['gameId' => $game->getId()]);
    }
}
