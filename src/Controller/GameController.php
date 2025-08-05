<?php

namespace App\Controller;

use App\Entity\Contest;
use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;

class GameController extends AbstractController
{
    #[Route('/game/create', name: 'game_create', methods: ['POST'])]
    public function createAction(Request $request, EntityManagerInterface $em)
    {
        $contestId = $request->request->get('contestId');
        $game = new Game();
        $game->setContest($em->getRepository(Contest::class)->find($contestId));
        $game->setCreatedAt(new \DateTimeImmutable());
        $game->setUpdatedAt(new \DateTimeImmutable());
        $game->setPublicToken(Uuid::v4());
        $game->setPresenterToken(Uuid::v4());
        $game->setFinished(false);

        $em->persist($game);
        $em->flush();

        return $this->redirectToRoute('game_presenter_lobby', ['gameId' => $game->getId()]);
    }




}
