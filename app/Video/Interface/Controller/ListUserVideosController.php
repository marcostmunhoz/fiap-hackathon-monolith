<?php

namespace App\Video\Interface\Controller;

use App\Shared\Interface\Response\JsonResponse;
use App\Video\Application\DTO\ListUserVideosInput;
use App\Video\Application\UseCase\ListUserVideosUseCase;
use App\Video\Infrastructure\Contracts\VideoUserAuthGuardInterface;

class ListUserVideosController
{
    public function __invoke(
        VideoUserAuthGuardInterface $authGuard,
        ListUserVideosInput $input,
        ListUserVideosUseCase $useCase
    ): JsonResponse {
        $output = $useCase->execute($authGuard->resolve(), $input);

        return JsonResponse::ok($output);
    }
}