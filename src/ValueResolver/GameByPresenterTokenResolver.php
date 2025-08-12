<?php

namespace App\ValueResolver;

use App\Repository\GameRepository;
use App\Service\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsTargetedValueResolver(self::TARGETED_VALUE_RESOLVER_NAME)]
class GameByPresenterTokenResolver implements ValueResolverInterface
{

public const TARGETED_VALUE_RESOLVER_NAME = 'game_by_presenter_token';

    public function __construct(
        private readonly GameRepository $gameRepository,
        private readonly TokenInterface $token
    )
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $presenterToken = $request->attributes->get('presenterToken');

        if ($presenterToken === null) {
            throw new NotFoundHttpException('Presenter token cannot be empty');
        }

        if (!$this->token->isValid($presenterToken)) {
            throw new NotFoundHttpException('Presenter token is invalid');
        }

        yield $this->gameRepository->findOneByPresenterToken($this->token->fromString($presenterToken));
    }
}
