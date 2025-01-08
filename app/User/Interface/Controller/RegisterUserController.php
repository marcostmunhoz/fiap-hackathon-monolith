<?php

namespace App\User\Interface\Controller;

use App\User\Application\DTO\RegisterUserInput;
use App\User\Application\UseCase\RegisterUserUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RegisterUserController
{
    public function __invoke(RegisterUserInput $input, RegisterUserUseCase $useCase): JsonResponse
    {
        $useCase->execute($input);

        return response()->json([], Response::HTTP_CREATED);
    }
}