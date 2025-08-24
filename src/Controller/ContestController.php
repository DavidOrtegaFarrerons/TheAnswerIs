<?php

namespace App\Controller;

use App\Entity\Contest;
use App\Enum\ContestStatus;
use App\Enum\ContestVisibility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ContestController extends AbstractController
{

    #[Route('/contests', name: 'contests', methods: ['GET'])]
    public function listAction(EntityManagerInterface $em) : Response
    {
        return $this->render('contest/list.html.twig', ['contests' => $em->getRepository(Contest::class)->findAll()]);
    }
}
