<?php

namespace App\Factory\Dto;

use App\Dto\Editor\QuestionDto;
use App\Entity\Question;

class QuestionDtoFactory
{
    public function fromEntity(Question $question): QuestionDto
    {
        return (new QuestionDto())
            ->setTitle($question->getTitle())
            ->setOptionA($question->getOptionA())
            ->setOptionB($question->getOptionB())
            ->setOptionC($question->getOptionC())
            ->setOptionD($question->getOptionD())
            ->setCorrectAnswer($question->getCorrectAnswer())
            ->setDifficulty($question->getDifficulty())
        ;
    }
}
