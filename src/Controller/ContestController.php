<?php

namespace App\Controller;

use App\Entity\Contest;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContestController extends AbstractController
{

    #[Route('/contests', name: 'contests', methods: ['GET'])]
    public function listAction(EntityManagerInterface $em) : Response
    {
        return $this->render('contest/list.html.twig', ['contests' => $em->getRepository(Contest::class)->findAll()]);
    }
}
