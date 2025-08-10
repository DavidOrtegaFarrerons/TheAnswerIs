<?php

namespace App\ValueResolver;

use App\Dto\CreateGameDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsTargetedValueResolver(CreateGameDtoResolver::TARGETED_VALUE_RESOLVER_NAME)]
readonly class CreateGameDtoResolver implements ValueResolverInterface
{

    public const TARGETED_VALUE_RESOLVER_NAME = 'contestId';

    public function __construct(
        private ValidatorInterface $validator,
    )
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $dto = new CreateGameDto();

        $dto->setContestId($request->request->get('contestId'));

        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            throw new BadRequestHttpException((string) $violations);
        }

        yield $dto;
    }
}
