<?php

namespace App\Entity;

use App\Enum\Joker;
use App\Repository\RoundRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RoundRepository::class)]
class Round
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'rounds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\Column]
    private ?int $questionNumber = null;

    #[ORM\Column]
    private array $shownAnswers = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $preselectedAnswer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $confirmedAnswer = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isCorrect = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $finishedAt = null;

    #[ORM\Column]
    private ?array $usedJokers = [];

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getQuestionNumber(): ?int
    {
        return $this->questionNumber;
    }

    public function setQuestionNumber(int $questionNumber): static
    {
        $this->questionNumber = $questionNumber;

        return $this;
    }

    public function getShownAnswers(): array
    {
        return $this->shownAnswers;
    }

    public function setShownAnswers(array $shownAnswers): static
    {
        $this->shownAnswers = $shownAnswers;

        return $this;
    }

    public function getPreselectedAnswer(): ?string
    {
        return $this->preselectedAnswer;
    }

    public function setPreselectedAnswer(?string $preselectedAnswer): static
    {
        $this->preselectedAnswer = $preselectedAnswer;

        return $this;
    }

    public function getConfirmedAnswer(): ?string
    {
        return $this->confirmedAnswer;
    }

    public function setConfirmedAnswer(?string $confirmedAnswer): static
    {
        $this->confirmedAnswer = $confirmedAnswer;

        return $this;
    }

    public function isCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(?bool $isCorrect): static
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?\DateTimeImmutable $finishedAt): static
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    public function getUsedJokers(): ?array
    {
        return $this->usedJokers;
    }

    public function setUsedJokers(?array $usedJokers): static
    {
        $this->usedJokers = $usedJokers;

        return $this;
    }

    public function hasUsed(Joker $joker): bool
    {
        return in_array($joker->value, $this->usedJokers, true);
    }

    public function useJoker(Joker $joker): void
    {
        if (!$this->hasUsed($joker)) {
            $this->usedJokers[] = $joker->value;
        }
    }
}

