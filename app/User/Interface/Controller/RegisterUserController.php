<?php

namespace App\User\Interface\Controller;

use App\Shared\Interface\Response\JsonResponse;
use App\User\Application\DTO\RegisterUserInput;
use App\User\Application\UseCase\RegisterUserUseCase;

class RegisterUserController
{
    public function __invoke(RegisterUserInput $input, RegisterUserUseCase $useCase): JsonResponse
    {
        $useCase->execute($input);

        return JsonResponse::created();
    }
}