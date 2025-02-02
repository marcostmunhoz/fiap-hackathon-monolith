<?php

namespace App\Video\Interface\Controller;

use App\Shared\Interface\Response\JsonResponse;
use App\Video\Application\DTO\UploadUserVideoInput;
use App\Video\Application\UseCase\UploadUserVideoUseCase;
use App\Video\Infrastructure\Contracts\VideoUserAuthGuardInterface;

class UploadUserVideoController
{
    public function __invoke(
        VideoUserAuthGuardInterface $authGuard,
        UploadUserVideoInput $input,
        UploadUserVideoUseCase $useCase
    ): JsonResponse {
        $output = $useCase->execute($authGuard->resolve(), $input);

        return JsonResponse::created($output);
    }
}