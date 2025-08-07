<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\RoundRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class GameContestantController extends AbstractController
{
    #[Route('/game/contestant/lobby/{publicToken}', name: 'game_contestant_lobby', methods: ['GET'])]
    public function contestantLobbyAction(string $publicToken, EntityManagerInterface $em) {
        $game = $em->getRepository(Game::class)->findOneBy(['publicToken' => $publicToken]);
        return $this->render('game/contestant/lobby.html.twig', ['game' => $game]);
    }

    #[Route('/api/game/{game}/contestant-joined', name: 'contestant_joined', methods: ['POST'])]
    public function contestantJoined(Game $game, HubInterface $hub) {
        $update = new Update(
            "/game/{$game->getId()}",
            json_encode([
                'type' => 'CONTESTANT_JOINED',
                'payload' => ['timestamp' => time()],
            ])
        );

        $hub->publish($update);

        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/game/contestant/start/{publicToken}', name: 'game.contestant.start', methods: ['GET'])]
    public function startAction(string $publicToken, EntityManagerInterface $em, RoundRepository $roundRepository) {
        $game = $em->getRepository(Game::class)->findOneBy(['publicToken' => $publicToken]);
        $roundsPlayed = $game->getRounds()->count() - 1; //This is because we don't count the current round as already played
        return $this->render('game/contestant/play.html.twig', [
            'game' => $game,
            'round' => $roundRepository->findCurrentRoundByGame($game),
            'roundsPlayed' => $roundsPlayed,
        ]);
    }

    #[Route('/game/contestant/end', name: 'game.contestant.end', methods: ['GET'])]
    public function endAction() {
        return $this->render('game/contestant/end.html.twig');
    }

}
