<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateGameDto
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    private string $contestId;

    public function getContestId(): string
    {
        return $this->contestId;
    }

    public function setContestId(string $contestId): void
    {
        $this->contestId = $contestId;
    }
}
