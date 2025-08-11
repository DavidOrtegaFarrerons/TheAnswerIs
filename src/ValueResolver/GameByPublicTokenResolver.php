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
class GameByPublicTokenResolver implements ValueResolverInterface
{

public const TARGETED_VALUE_RESOLVER_NAME = 'game_by_public_token';

    public function __construct(
        private readonly GameRepository $gameRepository,
    )
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $publicToken = $request->get('publicToken');

        if ($publicToken === null) {
            throw new NotFoundHttpException('Public token cannot be empty');
        }

        if (!Uuid::isValid($publicToken)) {
            throw new NotFoundHttpException('Public token is invalid');
        }

        yield $this->gameRepository->findOneByPublicToken(Uuid::fromString($publicToken));
    }
}
