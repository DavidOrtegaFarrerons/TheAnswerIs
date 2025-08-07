<?php

namespace App\Controller\Admin;

use App\Entity\Contest;
use App\Entity\Question;
use App\Repository\ContestRepository;
use App\Repository\QuestionRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly ContestRepository  $contestRepository,
        private readonly QuestionRepository $questionRepository,
    ) {}

    #[Route('/', name: 'dashboard')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'contestsCount'  => $this->contestRepository->count(),
            'questionsCount' => $this->questionRepository->count(),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('Quizzes');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Inicio', 'fa fa-home');
        yield MenuItem::linkToCrud('Contests', 'fa fa-trophy', Contest::class);
        yield MenuItem::linkToCrud('Questions', 'fa fa-question', Question::class);
    }
}
