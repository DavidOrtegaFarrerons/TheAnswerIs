<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Round;
use App\Enum\Joker;
use App\Repository\RoundRepository;
use App\Service\Joker\UseJokerService;
use App\Service\NextRoundService;
use App\Service\RevealOptionService;
use App\Service\SelectAnswerService;
use App\Service\StartPresenterGameService;
use App\Service\SubmitAnswerService;
use App\ValueResolver\GameByIdResolver;
use App\ValueResolver\GameByPresenterTokenResolver;
use App\ValueResolver\GameByPublicTokenResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

class GamePresenterController extends AbstractController
{
    const GAME_PRESENTER_LOBBY_ROUTE_NAME = 'game_presenter_lobby';

    #[Route('/game/presenter/lobby/{gameId}', name: self::GAME_PRESENTER_LOBBY_ROUTE_NAME, methods: ['GET'])]
    public function lobbyAction(
        #[ValueResolver(GameByIdResolver::TARGETED_VALUE_RESOLVER_NAME)] $game
    ) {
        return $this->render('game/presenter/lobby.html.twig', [
            'game' => $game,
        ]);
    }

    #[Route('/game/presenter/start/{presenterToken}', name: 'game.presenter.start', methods: ['GET'])]
    public function startAction(
        #[ValueResolver(GameByPresenterTokenResolver::TARGETED_VALUE_RESOLVER_NAME)]
        Game $game,
        StartPresenterGameService $startGameService
    ) {
        $dto = $startGameService->start($game);

        return $this->render('game/presenter/play.html.twig', [
            'game' => $dto->getGame(),
            'round' => $dto->getRound(),
            'roundsPlayed' => $dto->getRoundsPlayed(),
        ]);
    }

    #[Route('/game/presenter/{presenterToken}/reveal-answer/{option}', name: 'game.presenter.reveal.answer', methods: ['POST'])]
    public function revealAnswerAction(
        #[ValueResolver(GameByPresenterTokenResolver::TARGETED_VALUE_RESOLVER_NAME)]
        Game $game,
        string $option,
        RevealOptionService $revealOptionService
    ): JsonResponse
    {
        $revealOptionService->revealAnswer($game, $option);

        return new JsonResponse(['status' => 'ok']);
    }

    #[Route(
        '/game/presenter/{presenterToken}/use-joker/{joker}',
        name: 'game.presenter.use_joker',
        methods: ['POST']
    )]
    public function useJokerAction(
        #[ValueResolver(GameByPresenterTokenResolver::TARGETED_VALUE_RESOLVER_NAME)]
        Game $game,
        string $joker,
        UseJokerService $useJokerService,
    ): JsonResponse {
        $joker = Joker::from($joker);

        if (!in_array($joker->value, $game->getContest()->getAllowedJokers(), true)) {
            return $this->json(['error' => 'joker_not_allowed'], 400);
        }

        $useJokerService->use($joker, $game);

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
    public function selectAnswerAction(
        #[ValueResolver(GameByPresenterTokenResolver::TARGETED_VALUE_RESOLVER_NAME)]
        Game $game,
        string $option,
        SelectAnswerService $selectAnswerService,
    ) {
        $selectAnswerService->selectAnswer($game, $option);

        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/game/presenter/{presenterToken}/submit-answer/{option}', name: 'game.presenter.submit.answer', methods: ['POST'])]
    public function submitAnswerAction(
        #[ValueResolver(GameByPresenterTokenResolver::TARGETED_VALUE_RESOLVER_NAME)]
        Game $game,
        string $option,
        SubmitAnswerService $submitAnswerService,
    ): JsonResponse
    {
        $submitAnswerService->submitAnswer($game, $option);

        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/game/presenter/{presenterToken}/next-round', name: 'game.presenter.next-round', methods: ['POST'])]
    public function nextRoundAction(
        #[ValueResolver(GameByPresenterTokenResolver::TARGETED_VALUE_RESOLVER_NAME)]
        Game $game,
        NextRoundService $nextRoundService,
    ) : JsonResponse
    {
        $nextRoundService->nextRound($game);

        return $this->json(['status' => 'ok']);
    }

    #[Route('/game/presenter/end', name: 'game.presenter.end', methods: ['GET'])]
    public function endAction() {
        return $this->render('game/presenter/end.html.twig');
    }
}
