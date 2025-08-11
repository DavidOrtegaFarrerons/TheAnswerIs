<?php

namespace App\ValueResolver;

use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

#[AsTargetedValueResolver(self::TARGETED_VALUE_RESOLVER_NAME)]
readonly class GameByIdResolver implements ValueResolverInterface
{
    public const TARGETED_VALUE_RESOLVER_NAME = 'game_by_id';
    public function __construct(private GameRepository $gameRepository)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $gameId = $request->get('gameId');

        if ($gameId === null) {
            throw new NotFoundHttpException("Invalid gameId");
        }

        yield $this->gameRepository->findOneById(Uuid::fromString($gameId));
    }
}
