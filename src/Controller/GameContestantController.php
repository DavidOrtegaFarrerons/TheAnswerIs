<?php

namespace App\Controller;

use App\Entity\Game;
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
    public function startAction(string $publicToken, EntityManagerInterface $em) {
        $game = $em->getRepository(Game::class)->findOneBy(['publicToken' => $publicToken]);
        return $this->render('game/contestant/play.html.twig', ['game' => $game]);
    }

}
