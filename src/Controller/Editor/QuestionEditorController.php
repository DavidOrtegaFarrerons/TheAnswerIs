<?php

namespace App\Controller\Editor;

use App\Dto\Editor\QuestionFieldDto;
use App\Entity\Contest;
use App\Entity\Question;
use App\Enum\Difficulty;
use App\Factory\Dto\QuestionDtoFactory;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/contests/{contest}/questions')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class QuestionEditorController extends AbstractController
{
    #[Route('/summary', methods: ['GET'])]
    public function summary(Contest $contest)
    {
        if ($contest->getCreatedBy() !== $this->getUser()) {
            $this->createAccessDeniedException();
        }
        $questions = $contest->getQuestions();

        $total = 0;
        $map = [
            'easy' => 0,
            'medium' => 0,
            'hard' => 0,
        ];

        foreach ($questions as $question) {
            if ($question->getDifficulty() === null) {
                continue;
            }

            $map[$question->getDifficulty()->value]++;
            $total++;
        }

        $map['total'] = $total;

        return $this->json($map);

    }

    #[Route('/{question}', methods: ['GET'])]
    public function read(Question $question, QuestionDtoFactory $questionDtoFactory): JsonResponse
    {
        return $this->json($questionDtoFactory->fromEntity($question));
    }

    #[Route('', methods: ['POST'])]
    public function create(Contest $contest, EntityManagerInterface $em)
    {
        $question = new Question();
        $question->setContest($contest);

        $em->persist($question);
        $em->flush();


        return $this->json(['id' => $question->getId()], 201);
    }

    #[Route('/{question}', methods: ['PATCH'])]
    public function update(
        Question $question,
        #[MapRequestPayload] QuestionFieldDto $dto,
        EntityManagerInterface $em
    )
    {
        $value = $dto->getValue();

        if ($dto->getField() === 'difficulty') {
            $value = Difficulty::from($value);
        }
        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue($question, $dto->getField(), $value);
        $em->flush();


        return $this->json(['id' => $question->getId()], 201);
    }

    #[Route('/{question}', methods: ['DELETE'])]
    public function delete(
        Contest $contest,
        Question $question,
        EntityManagerInterface $em
    )
    {
        if ($question->getContest() !== $contest) {
            throw new InvalidArgumentException("The given question does not belong to the contest");
        }

        $em->remove($question);
        $em->flush();


        return $this->json([], 204);
    }
}
