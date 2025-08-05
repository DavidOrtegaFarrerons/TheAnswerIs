<?php

namespace App\Controller;

use App\Entity\Contest;
use App\Entity\Game;
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
        if ($game->getRounds()->count() === 0) {
            $roundRepository->createRound($game->getContest(), $game);
        }

        $hub->publish(new Update(
            "/game/{$game->getId()}/{$game->getPublicToken()}",
            json_encode([
                'type' => 'GAME_STARTED',
                'payload' => []
            ])
        ));

        return $this->render('game/presenter/play.html.twig', ['game' => $game]);
    }

    #[Route('/game/presenter/{presenterToken}/reveal-question', name: 'game.presenter.reveal.question', methods: ['POST'])]
    public function revealQuestionAction(string $presenterToken, EntityManagerInterface $em, RoundRepository $roundRepository, HubInterface $hub) {
        $game = $em->getRepository(Game::class)->findOneBy(['presenterToken' => $presenterToken]);
        $round = $roundRepository->findCurrentRoundByGame($game);
        $question = $round->getQuestion()->getTitle();

        $hub->publish(new Update(
            "/game/{$game->getId()}/{$game->getPresenterToken()}",
            json_encode([
                'type' => 'QUESTION_REVEALED',
                'payload' => [
                    'question' => $question,
                ]
            ])
        ));

        return new JsonResponse(['status' => 'ok']);
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
    public function nextRoundAction(string $presenterToken, EntityManagerInterface $em, RoundRepository $roundRepository, HubInterface $hub) {
        $game = $em->getRepository(Game::class)->findOneBy(['presenterToken' => $presenterToken]);
        $roundRepository->createRound($game->getContest(), $game);

        $hub->publish(new Update(
            "/game/{$game->getId()}/{$game->getPublicToken()}",
            json_encode([
                'type' => 'NEXT_ROUND',
                'payload' => []
            ])
        ));
    }
}
