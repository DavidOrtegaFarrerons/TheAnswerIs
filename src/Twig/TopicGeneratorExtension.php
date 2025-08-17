<?php

namespace App\Twig;

use App\TopicGenerator\GameTopicGenerator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TopicGeneratorExtension extends AbstractExtension
{

    public function __construct(
        private readonly GameTopicGenerator $gameTopicGenerator,
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('game_presenter_url', [$this->gameTopicGenerator, 'generateForPresenter']),
            new TwigFunction('game_contestant_url', [$this->gameTopicGenerator, 'generateForContestant']),
        ];
    }
}
