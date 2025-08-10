<?php

namespace App\Controller;

use App\Exception\ContestNotFoundException;
use App\Service\GameCreationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{

    public function __construct(private readonly GameCreationService $gameCreationService)
    {
    }

    /**
     * @throws ContestNotFoundException
     */
    #[Route('/game/create', name: 'game_create', methods: ['POST'])]
    public function createAction(Request $request)
    {
        $contestId = $request->request->get('contestId');
        $game = $this->gameCreationService->create($contestId);

        return $this->redirectToRoute('game_presenter_lobby', ['gameId' => $game->getId()]);
    }
}
