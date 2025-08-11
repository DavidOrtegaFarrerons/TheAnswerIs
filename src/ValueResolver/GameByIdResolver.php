<?php

namespace App\ValueResolver;

use App\Repository\GameRepository;
use App\Service\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Uid\Uuid;

#[AsTargetedValueResolver(self::TARGETED_VALUE_RESOLVER_NAME)]
readonly class GameByIdResolver implements ValueResolverInterface
{
    public const TARGETED_VALUE_RESOLVER_NAME = 'game_by_id';
    public function __construct(
        private GameRepository $gameRepository,
        private TokenInterface $token
    )
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $gameId = $request->get('gameId');

        if ($gameId === null) {
            throw new BadRequestHttpException("gameId cannot be empty");
        }

        if (!$this->token->isValid($gameId)) {
            throw new NotFoundHttpException('gameId is not valid');
        }

        yield $this->gameRepository->findOneById($this->token->fromString($gameId));
    }
}
