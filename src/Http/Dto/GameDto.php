<?php

namespace App\Http\Dto;

class GameDto
{
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
