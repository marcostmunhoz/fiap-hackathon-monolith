<?php

namespace App\Shared\Infrastructure\ServiceProvider;

use App\Shared\Domain\Service\EntityIdGeneratorInterface;
use App\Shared\Domain\Service\JwtGeneratorInterface;
use App\Shared\Domain\Service\MessageProducerInterface;
use App\Shared\Infrastructure\Config\AppConfig;
use App\Shared\Infrastructure\Service\DatabaseMessageProducer;
use App\Shared\Infrastructure\Service\SignedJwtGenerator;
use App\Shared\Infrastructure\Service\UuidV4EntityIdGenerator;
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
        $this->app->bind(
            EntityIdGeneratorInterface::class,
            UuidV4EntityIdGenerator::class,
        );

        $this->app->bind(
            JwtGeneratorInterface::class,
            SignedJwtGenerator::class,
        );

        $this->app->bind(
            MessageProducerInterface::class,
            function () {
                return new DatabaseMessageProducer();
            }
        );

        $this->app->singleton(AppConfig::class);
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
}
