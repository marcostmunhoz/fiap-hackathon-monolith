<?php

namespace App\User\Interface\Controller;

use App\User\Application\DTO\AuthenticateUserInput;
use App\User\Application\UseCase\AuthenticateUserUseCase;
use Illuminate\Http\JsonResponse;

class AuthenticateUserController
{
    public function __invoke(AuthenticateUserInput $input, AuthenticateUserUseCase $useCase): JsonResponse
    {
        $output = $useCase->execute($input);

        return response()->json(['data' => $output]);
    }
}