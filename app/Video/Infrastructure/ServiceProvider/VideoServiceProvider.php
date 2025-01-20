<?php

namespace App\Video\Infrastructure\ServiceProvider;

use App\Shared\Domain\Service\MessageProducerInterface;
use App\Shared\Infrastructure\Service\FakeMessageProducer;
use App\Video\Application\UseCase\UploadUserVideoUseCase;
use App\Video\Domain\Repository\VideoRepositoryInterface;
use App\Video\Infrastructure\Repository\QueryBuilderVideoRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class VideoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            VideoRepositoryInterface::class,
            QueryBuilderVideoRepository::class,
        );

        $this->app->when(UploadUserVideoUseCase::class)
            ->needs(Filesystem::class)
            ->give(static fn () => Storage::disk('videos'));

        $this->app->when(UploadUserVideoUseCase::class)
            ->needs(MessageProducerInterface::class)
            ->give(FakeMessageProducer::class);
    }
}