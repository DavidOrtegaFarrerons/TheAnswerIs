<?php

namespace App\Controller;

use App\Repository\ContestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    const LATEST_CONTESTS_NUMBER = 5;

    public function __construct(private readonly ContestRepository $contestRepository)
    {
    }

    #[Route('/', name: 'home', methods: ['GET'])]
    public function homeAction(): Response
    {
        return $this->render('home/home.html.twig', [
            'latestContests' => $this->contestRepository->getLatestContests(self::LATEST_CONTESTS_NUMBER),
        ]);
    }
}
