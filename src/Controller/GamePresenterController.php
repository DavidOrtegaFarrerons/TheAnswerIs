<?php

namespace App\Controller;

use App\Entity\Contest;
use App\Entity\Game;
use App\Entity\Round;
use App\Enum\Joker;
use App\Repository\RoundRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GamePresenterController extends AbstractController
{
    #[Route('/game/presenter/lobby/{gameId}', name: 'game_presenter_lobby', methods: ['GET'])]
    public function lobbyAction(string $gameId, EntityManagerInterface $em) {

        $game = $em->getRepository(Game::class)->find($gameId);
        $publicUrl = $this->generateUrl('game_contestant_lobby', [
            'publicToken' => $game->getPublicToken(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);


        return $this->render('game/presenter/lobby.html.twig', [
            'publicUrl' => $publicUrl,
            'game' => $game,
        ]);
    }

    #[Route('/game/presenter/start/{presenterToken}', name: 'game.presenter.start', methods: ['GET'])]
    public function startAction(string $presenterToken, EntityManagerInterface $em, RoundRepository $roundRepository, HubInterface $hub) {
        $game = $em->getRepository(Game::class)->findOneBy(['presenterToken' => $presenterToken]);
        $roundsPlayed = $game->getRounds()->count();
        if ($roundsPlayed === 0) {
            $round = $roundRepository->createRound($game->getContest(), $game);
        } else {
            $round = $roundRepository->findCurrentRoundByGame($game);
        }

        $hub->publish(new Update(
            "/game/{$game->getId()}/{$game->getPublicToken()}",
            json_encode([
                'type' => 'GAME_STARTED',
                'payload' => []
            ])
        ));

        return $this->render('game/presenter/play.html.twig', [
            'game' => $game,
            'round' => $round,
            'roundsPlayed' => $roundsPlayed,
        ]);
    }

    #[Route('/game/presenter/{presenterToken}/reveal-answer/{option}', name: 'game.presenter.reveal.answer', methods: ['POST'])]
    public function revealAnswerAction(string $presenterToken, string $option, EntityManagerInterface $em, RoundRepository $roundRepository, HubInterface $hub) {
        $game = $em->getRepository(Game::class)->findOneBy(['presenterToken' => $presenterToken]);
        $round = $roundRepository->findCurrentRoundByGame($game);
        $optionAnswer = $round->getQuestion()->getOptionByString($option);
        $payload = [
            'type' => 'ANSWER_REVEALED',
            'payload' => [
                'key' => $option,
                'text' => $optionAnswer,
            ]
        ];

        if ($option === $round->getQuestion()->getCorrectAnswer()) {
            $payload['payload']['correct'] = true;
        }

        $hub->publish(new Update(
            "/game/{$game->getId()}/{$game->getPresenterToken()}",
            json_encode($payload)
        ));

        return new JsonResponse(['status' => 'ok']);
    }

    #[Route(
        '/game/presenter/{presenterToken}/use-joker/{joker}',
        name: 'game.presenter.use_joker',
        methods: ['POST']
    )]
    public function useJokerAction(
        string $presenterToken,
        string $joker,
        EntityManagerInterface $em,
        RoundRepository $roundRepository,
        HubInterface $hub,
    ): JsonResponse {
        $jokerEnum = Joker::from($joker);
        $game      = $em->getRepository(Game::class)
            ->findOneBy(['presenterToken' => $presenterToken]);
        $round     = $roundRepository->findCurrentRoundByGame($game);

        if (!in_array($jokerEnum->value, $game->getContest()->getAllowedJokers(), true)) {
            return $this->json(['error' => 'joker_not_allowed'], 400);
        }
        if ($round->hasUsed($jokerEnum)) {
            return $this->json(['error' => 'joker_already_used'], 400);
        }

        $payload = match ($jokerEnum) {
            Joker::FIFTY_FIFTY => $this->applyFiftyFifty($round),
            Joker::ROULETTE    => $this->applyRoulette($round),
            Joker::PHONE       => $this->applyPhone($round),
        };

        $round->useJoker($jokerEnum);
        $em->flush();

        $hub->publish(new Update(
            "/game/{$game->getId()}/{$game->getPresenterToken()}",
            json_encode([
                'type'    => 'JOKER_USED',
                'payload' => $payload + ['joker' => $jokerEnum->value],
            ])
        ));

        return $this->json(['status' => 'ok']);
    }

    /** returns array<int,string> keys to hide */
    private function applyFiftyFifty(Round $round): array
    {
        $wrong = array_diff(['a','b','c','d'], [$round->getQuestion()->getCorrectAnswer()]);
        shuffle($wrong);
        return ['remove' => array_slice($wrong, 0, 2)];
    }

    private function applyRoulette(Round $round): array
    {
        $wrong = array_diff(['a','b','c','d'], [$round->getQuestion()->getCorrectAnswer()]);
        shuffle($wrong);
        $count = random_int(0, 3);
        return ['remove' => array_slice($wrong, 0, $count), 'count' => $count];
    }

    private function applyPhone(Round $round): array
    {
        return [];
    }

    #[Route('/game/presenter/{presenterToken}/select-answer/{option}', name: 'game.presenter.select.answer', methods: ['POST'])]
    public function selectAnswerAction(string $presenterToken, string $option, EntityManagerInterface $em, RoundRepository $roundRepository, HubInterface $hub) {
        $game = $em->getRepository(Game::class)->findOneBy(['presenterToken' => $presenterToken]);
        $payload = [
            'type' => 'ANSWER_SELECTED',
            'payload' => [
                'key' => $option
            ]
        ];

        $hub->publish(new Update(
            "/game/{$game->getId()}/{$game->getPresenterToken()}",
            json_encode($payload)
        ));

        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/game/presenter/{presenterToken}/submit-answer/{option}', name: 'game.presenter.submit.answer', methods: ['POST'])]
    public function submitAnswerAction(string $presenterToken, string $option, EntityManagerInterface $em, RoundRepository $roundRepository, HubInterface $hub) {
        $game = $em->getRepository(Game::class)->findOneBy(['presenterToken' => $presenterToken]);
        $round = $roundRepository->findCurrentRoundByGame($game);

        $correct = $option === $round->getQuestion()->getCorrectAnswer();

        $payload = [
            'type' => 'ANSWER_SUBMITTED',
            'payload' => [
                'option' => $option,
                'correct' => $correct,
            ]
        ];

        $hub->publish(new Update(
            "/game/{$game->getId()}/{$game->getPresenterToken()}",
            json_encode($payload)
        ));

        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/game/presenter/{presenterToken}/next-round', name: 'game.presenter.next-round', methods: ['POST'])]
    public function nextRoundAction(string $presenterToken, EntityManagerInterface $em, RoundRepository $roundRepository, HubInterface $hub) : JsonResponse
    {
        $game = $em->getRepository(Game::class)->findOneBy(['presenterToken' => $presenterToken]);
        $roundsPlayed = $game->getRounds()->count();
        if ($game->getContest()->getTotalQuestions() <= $game->getRounds()->count()) {
            $hub->publish(new Update(
                "/game/{$game->getId()}/{$game->getPresenterToken()}",
                json_encode([
                    'type' => 'END_OF_GAME',
                    'payload' => []
                ])
            ));

            return $this->json(['status' => 'end']);
        }

        $round = $roundRepository->createRound($game->getContest(), $game);
        $payload = [
            'type' => 'NEXT_ROUND',
            'payload' => [
                'questionText' => $round->getQuestion()->getTitle(),
                'roundsPlayed' => $roundsPlayed,
            ]
        ];

        $hub->publish(new Update(
            "/game/{$game->getId()}/{$game->getPresenterToken()}",
            json_encode($payload)
        ));

        return $this->json(['status' => 'ok']);
    }

    #[Route('/game/presenter/end', name: 'game.presenter.end', methods: ['GET'])]
    public function endAction() {
        return $this->render('game/presenter/end.html.twig');
    }
}
