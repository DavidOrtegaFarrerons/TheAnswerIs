<?php

namespace App\Event\Game;

use Symfony\Component\Uid\Uuid;

interface GameEventInterface
{
    public function getGameId(): Uuid;
    public function getPresenterToken(): Uuid;
    public function getPublicToken(): Uuid;
}
