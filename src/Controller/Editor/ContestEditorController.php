<?php

namespace App\Controller\Editor;

use App\Entity\Contest;
use App\Enum\ContestStatus;
use App\Enum\ContestVisibility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/contests')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ContestEditorController extends AbstractController
{
    #[Route('', name: 'create_contest', methods: ['POST'])]
    public function createAction(
        Request $request,
        Security $security,
        EntityManagerInterface $em
    ): Response {
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('create_contest', $submittedToken)) {
            $this->addFlash('danger', 'Invalid form submission, please reload the page or try again later.');
            return $this->redirectToRoute('user_profile', ['userId' => $security->getUser()->getId()]);
        }

        $contestName = trim((string) $request->request->get('name', ''));
        $contestImageUrl = trim((string) $request->request->get('imageUrl', ''));
        $visibility = ContestVisibility::from($request->request->get('visibility', ''));
        if ($contestName === '') {
            $this->addFlash('danger', 'The name of the contest can\'t be empty.');
            return $this->redirectToRoute('user_profile', ['userId' => $security->getUser()->getId()]);
        }

        $contest = new Contest();
        $contest->setName($contestName);
        $contest->setImageUrl($contestImageUrl);
        $contest->setVisibility($visibility);
        $contest->setTotalQuestions(15);
        $contest->setCreatedBy($security->getUser());
        $contest->setStatus(ContestStatus::DRAFT);

        $em->persist($contest);
        $em->flush();

        return $this->redirectToRoute('edit_contest', ['contest' => $contest->getId()]);
    }

    #[Route('/edit/{contest}', name: 'update_contest', methods: ['POST'])]
    public function updateAction(
        Request $request,
        Security $security,
        Contest $contest,
        EntityManagerInterface $em
    ): Response {
        $contestName = trim((string) $request->request->get('name', ''));
        $contestImageUrl = trim((string) $request->request->get('imageUrl', ''));
        $visibility = ContestVisibility::from($request->request->get('visibility', ''));
        if ($contestName === '') {
            return $this->json(['message' => 'contest name cannot be empty'], 400);
        }

        $contest->setName($contestName);
        $contest->setImageUrl($contestImageUrl);
        $contest->setVisibility($visibility);
        $contest->setTotalQuestions(15);
        $contest->setCreatedBy($security->getUser());
        $contest->setStatus(ContestStatus::DRAFT);

        $em->flush();

        return $this->json(['success' => true]);
    }


    #[Route('/edit/{contest}', name: 'edit_contest', methods: ['GET'])]
    public function editAction(Contest $contest) : Response
    {
        if ($contest->getCreatedBy() !== $this->getUser()) {
            throw new UnauthorizedHttpException("You are not the owner of this contest");
        }

        return $this->render('/contest/editor.html.twig', [
            'contest' => $contest,
            'questions' => $contest->getQuestions(),
            'currentQuestion' => $contest->getQuestions()->last()
        ]);
    }

    #[Route('/edit/{contest}/publish', name: 'publish_contest', methods: ['POST'])]
    public function publishAction(Contest $contest, EntityManagerInterface $em) : Response
    {
        if ($contest->getCreatedBy() !== $this->getUser()) {
            return $this->json(['message' => 'You are not the owner of this contest'], 403);
        }

        if ($contest->getQuestions()->count() < 15) {
            return $this->json(['message' => 'You can\'t publish less than 15 questions'], 400);
        }

        $map = [
            'easy' => 0,
            'medium' => 0,
            'hard' => 0,
        ];

        foreach ($contest->getQuestions() as $question) {
            if (
                $question->getTitle() === null
                || $question->getOptionA() === null
                || $question->getOptionB() === null
                || $question->getOptionC() === null
                || $question->getOptionD() === null
                || $question->getCorrectAnswer() === null
                || $question->getDifficulty() === null
            ) {
                return $this->json(['message' => 'The questions must be fulfilled, please review them'], 400);
            }

            $map[$question->getDifficulty()->value]++;
        }

        foreach ($map as $difficulty => $count) {
            if ($count < 5) {
                return $this->json(['message' => "You need at least 5 $difficulty questions"], 400);
            }
        }

        $contest->setStatus(ContestStatus::PUBLISHED);
        $em->persist($contest);
        $em->flush();

        return $this->json(['status' => ContestStatus::PUBLISHED->value]);
    }

    #[Route('/edit/{contest}/unpublish', name: 'unpublish_contest', methods: ['POST'])]
    public function unpublishAction(Contest $contest, EntityManagerInterface $em) : Response
    {
        if ($contest->getCreatedBy() !== $this->getUser()) {
            throw new UnauthorizedHttpException("You are not the owner of this contest");
        }

        $contest->setStatus(ContestStatus::DRAFT);
        $em->persist($contest);
        $em->flush();

        return $this->json(['status' => ContestStatus::DRAFT->value]);
    }
}
