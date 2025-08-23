<?php

namespace App\Entity;

use App\Enum\Difficulty;
use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $optionA;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $optionB;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $optionC;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $optionD;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $correctAnswer;

    #[Column(type: 'string', nullable: true, enumType: Difficulty::class)]
    private ?Difficulty $difficulty;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(name: 'contest_id', nullable: false)]
    private ?Contest $contest = null;

    public function getOptionText(string $option) {
        return match ($option) {
            'a' => $this->getOptionA(),
            'b' => $this->getOptionB(),
            'c' => $this->getOptionC(),
            'd' => $this->getOptionD(),
            default => throw new \RuntimeException("Unknown option '{$option}'"),
        };
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): Question
    {
        $this->title = $title;
        return $this;
    }

    public function getOptionA(): ?string
    {
        return $this->optionA;
    }

    public function setOptionA(?string $optionA): Question
    {
        $this->optionA = $optionA;
        return $this;
    }

    public function getOptionB(): ?string
    {
        return $this->optionB;
    }

    public function setOptionB(?string $optionB): Question
    {
        $this->optionB = $optionB;
        return $this;
    }

    public function getOptionC(): ?string
    {
        return $this->optionC;
    }

    public function setOptionC(?string $optionC): Question
    {
        $this->optionC = $optionC;
        return $this;
    }

    public function getOptionD(): ?string
    {
        return $this->optionD;
    }

    public function setOptionD(?string $optionD): Question
    {
        $this->optionD = $optionD;
        return $this;
    }

    public function getCorrectAnswer(): ?string
    {
        return $this->correctAnswer;
    }

    public function setCorrectAnswer(?string $correctAnswer): Question
    {
        $this->correctAnswer = $correctAnswer;
        return $this;
    }

    public function getDifficulty(): ?Difficulty
    {
        return $this->difficulty;
    }

    public function setDifficulty(?Difficulty $difficulty): Question
    {
        $this->difficulty = $difficulty;
        return $this;
    }

    public function getContest(): ?Contest
    {
        return $this->contest;
    }

    public function setContest(?Contest $contest): Question
    {
        $this->contest = $contest;
        return $this;
    }
}
