<?php

namespace App\Video\Interface\Controller;

use App\Shared\Domain\ValueObject\EntityId;
use App\Shared\Interface\Response\DownloadResponse;
use App\Video\Application\DTO\DownloadUserVideoInput;
use App\Video\Application\UseCase\DownloadUserVideoUseCase;
use App\Video\Infrastructure\Contracts\VideoUserAuthGuardInterface;

class DownloadUserVideoController
{
    public function __invoke(
        VideoUserAuthGuardInterface $authGuard,
        string $id,
        DownloadUserVideoUseCase $useCase
    ): DownloadResponse {
        $input = new DownloadUserVideoInput(new EntityId($id));
        $output = $useCase->execute($authGuard->resolve(), $input);

        return new DownloadResponse($output->stream);
    }
}