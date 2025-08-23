<?php

namespace App\Dto\Editor;

use App\Enum\Difficulty;

class QuestionDto
{
    private ?string $title;
    private ?string $optionA;
    private ?string $optionB;
    private ?string $optionC;
    private ?string $optionD;
    private ?string $correctAnswer;
    private ?Difficulty $difficulty;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): QuestionDto
    {
        $this->title = $title;
        return $this;
    }

    public function getOptionA(): ?string
    {
        return $this->optionA;
    }

    public function setOptionA(?string $optionA): QuestionDto
    {
        $this->optionA = $optionA;
        return $this;
    }

    public function getOptionB(): ?string
    {
        return $this->optionB;
    }

    public function setOptionB(?string $optionB): QuestionDto
    {
        $this->optionB = $optionB;
        return $this;
    }

    public function getOptionC(): ?string
    {
        return $this->optionC;
    }

    public function setOptionC(?string $optionC): QuestionDto
    {
        $this->optionC = $optionC;
        return $this;
    }

    public function getOptionD(): ?string
    {
        return $this->optionD;
    }

    public function setOptionD(?string $optionD): QuestionDto
    {
        $this->optionD = $optionD;
        return $this;
    }

    public function getCorrectAnswer(): ?string
    {
        return $this->correctAnswer;
    }

    public function setCorrectAnswer(?string $correctAnswer): QuestionDto
    {
        $this->correctAnswer = $correctAnswer;
        return $this;
    }

    public function getDifficulty(): ?Difficulty
    {
        return $this->difficulty;
    }

    public function setDifficulty(?Difficulty $difficulty): QuestionDto
    {
        $this->difficulty = $difficulty;
        return $this;
    }
}
