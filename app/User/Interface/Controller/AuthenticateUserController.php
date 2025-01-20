<?php

namespace App\User\Interface\Controller;

use App\Shared\Interface\Response\JsonResponse;
use App\User\Application\DTO\AuthenticateUserInput;
use App\User\Application\UseCase\AuthenticateUserUseCase;

class AuthenticateUserController
{
    public function __invoke(AuthenticateUserInput $input, AuthenticateUserUseCase $useCase): JsonResponse
    {
        $output = $useCase->execute($input);

        return JsonResponse::ok($output);
    }
}