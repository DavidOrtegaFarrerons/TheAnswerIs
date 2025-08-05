<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contest $contest = null;

    #[ORM\Column(type: 'uuid')]
    private ?Uuid $publicToken = null;

    #[ORM\Column(type: 'uuid')]
    private ?Uuid $presenterToken = null;

    #[ORM\OneToMany(targetEntity: Round::class, mappedBy: 'game', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $rounds;

    #[ORM\Column]
    private ?bool $finished = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getPublicToken(): ?Uuid
    {
        return $this->publicToken;
    }

    public function setPublicToken(Uuid $publicToken): static
    {
        $this->publicToken = $publicToken;

        return $this;
    }

    public function getPresenterToken(): ?Uuid
    {
        return $this->presenterToken;
    }

    public function setPresenterToken(?Uuid $presenterToken): Game
    {
        $this->presenterToken = $presenterToken;
        return $this;
    }

    public function getRounds(): Collection
    {
        return $this->rounds;
    }

    public function setRounds(Collection $rounds): Game
    {
        $this->rounds = $rounds;
        return $this;
    }

    public function isFinished(): ?bool
    {
        return $this->finished;
    }

    public function setFinished(bool $finished): static
    {
        $this->finished = $finished;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): Game
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): Game
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
