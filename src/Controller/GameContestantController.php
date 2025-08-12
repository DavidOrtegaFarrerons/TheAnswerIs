<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\RoundRepository;
use App\Service\ContestantJoinsService;
use App\Service\StartContestantGameService;
use App\ValueResolver\GameByPublicTokenResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class GameContestantController extends AbstractController
{
    const GAME_CONTESTANT_LOBBY_ROUTE_NAME = 'game_contestant_lobby';

    #[Route('/game/contestant/lobby/{publicToken}', name: self::GAME_CONTESTANT_LOBBY_ROUTE_NAME, methods: ['GET'])]
    public function contestantLobby(
        #[ValueResolver(GameByPublicTokenResolver::TARGETED_VALUE_RESOLVER_NAME)] $game
    ): Response
    {
        return $this->render('game/contestant/lobby.html.twig', ['game' => $game]);
    }

    #[Route('/api/game/{game}/contestant-joined', name: 'contestant_joined', methods: ['POST'])]
    public function contestantJoined(
        Game $game,
        ContestantJoinsService $contestantJoinsService
    ): JsonResponse
    {
        $contestantJoinsService->join($game);

        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/game/contestant/start/{publicToken}', name: 'game.contestant.start', methods: ['GET'])]
    public function startAction(
        #[ValueResolver(GameByPublicTokenResolver::TARGETED_VALUE_RESOLVER_NAME)]
        Game $game,
        StartContestantGameService $startContestantGameService
    ): Response
    {
        $dto = $startContestantGameService->start($game);

        return $this->render('game/contestant/play.html.twig', [
            'game' => $dto->getGame(),
            'round' => $dto->getRound(),
            'roundsPlayed' => $dto->getRoundsPlayed(),
        ]);
    }

    #[Route('/game/contestant/end', name: 'game.contestant.end', methods: ['GET'])]
    public function endAction(): Response
    {
        return $this->render('game/contestant/end.html.twig');
    }
}
