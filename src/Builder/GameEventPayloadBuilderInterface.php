<?php

namespace App\Builder;

use App\Enum\Audience;

interface GameEventPayloadBuilderInterface
{
    public function supports(object $event) : bool;
    public function build(object $event, Audience $audience) : array;
}
