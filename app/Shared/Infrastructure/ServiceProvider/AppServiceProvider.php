<?php

namespace App\Shared\Infrastructure\ServiceProvider;

use App\Shared\Domain\Service\EntityIdGeneratorInterface;
use App\Shared\Domain\Service\JwtGeneratorInterface;
use App\Shared\Domain\Service\MessageProducerInterface;
use App\Shared\Infrastructure\Config\AppConfig;
use App\Shared\Infrastructure\Service\FakeMessageProducer;
use App\Shared\Infrastructure\Service\SignedJwtGenerator;
use App\Shared\Infrastructure\Service\UuidV4EntityIdGenerator;
use App\User\Application\UseCase\RegisterUserUseCase;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Service\PasswordHasherInterface;
use App\User\Infrastructure\Repository\QueryBuilderUserRepository;
use App\User\Infrastructure\Service\BcryptPasswordHasher;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Facades\Health;

/**
 * @codeCoverageIgnore
 */
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerSharedKernel();
        $this->registerUserBoundedContext();
    }

    public function boot(): void
    {
        RateLimiter::for('default', static function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        Health::checks(
            app()->environment('local')
                ? [DatabaseCheck::new()]
                : [DatabaseCheck::new(), PingCheck::new()->url('https://google.com'),]
        );
    }

    private function registerSharedKernel(): void
    {
        $this->app->bind(
            EntityIdGeneratorInterface::class,
            UuidV4EntityIdGenerator::class,
        );

        $this->app->bind(
            JwtGeneratorInterface::class,
            SignedJwtGenerator::class,
        );

        $this->app->singleton(AppConfig::class);

        $this->app->when(RegisterUserUseCase::class)
            ->needs(MessageProducerInterface::class)
            ->give(FakeMessageProducer::class);
    }

    private function registerUserBoundedContext(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            QueryBuilderUserRepository::class,
        );

        $this->app->bind(
            PasswordHasherInterface::class,
            BcryptPasswordHasher::class
        );
    }
}
