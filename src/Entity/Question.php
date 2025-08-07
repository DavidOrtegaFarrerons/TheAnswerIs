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

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $optionA = null;

    #[ORM\Column(length: 255)]
    private ?string $optionB = null;

    #[ORM\Column(length: 255)]
    private ?string $optionC = null;

    #[ORM\Column(length: 255)]
    private ?string $optionD = null;

    #[ORM\Column(length: 255)]
    private ?string $correctAnswer = null;

    #[Column(type: 'string', enumType: Difficulty::class)]
    private Difficulty $difficulty;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(name: 'contest_id', nullable: false)]
    private ?Contest $contest = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getOptionA(): ?string
    {
        return $this->optionA;
    }

    public function setOptionA(string $optionA): static
    {
        $this->optionA = $optionA;

        return $this;
    }

    public function getOptionB(): ?string
    {
        return $this->optionB;
    }

    public function setOptionB(string $optionB): static
    {
        $this->optionB = $optionB;

        return $this;
    }

    public function getOptionC(): ?string
    {
        return $this->optionC;
    }

    public function setOptionC(string $optionC): static
    {
        $this->optionC = $optionC;

        return $this;
    }

    public function getOptionD(): ?string
    {
        return $this->optionD;
    }

    public function setOptionD(string $optionD): static
    {
        $this->optionD = $optionD;

        return $this;
    }

    public function getCorrectAnswer(): ?string
    {
        return $this->correctAnswer;
    }

    public function setCorrectAnswer(string $correctAnswer): static
    {
        $this->correctAnswer = $correctAnswer;

        return $this;
    }

    public function getDifficulty(): Difficulty
    {
        return $this->difficulty;
    }

    public function setDifficulty(Difficulty $difficulty): Question
    {
        $this->difficulty = $difficulty;
        return $this;
    }

    public function getOptionByString(string $option) {
        return match ($option) {
            'a' => $this->getOptionA(),
            'b' => $this->getOptionB(),
            'c' => $this->getOptionC(),
            'd' => $this->getOptionD(),
            default => throw new \RuntimeException("Unknown option '{$option}'"),
        };
    }

    public function getContest(): ?Contest
    {
        return $this->contest;
    }

    public function setContest(?Contest $contest): static
    {
        $this->contest = $contest;

        return $this;
    }
}
