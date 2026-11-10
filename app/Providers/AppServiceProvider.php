<?php

namespace App\Providers;

use App\Application\Services\MessagePublisherInterface;
use App\Domain\Repositories\ProductionOrderRepositoryInterface;
use App\Infrastructure\Messaging\Publishers\ProductionRabbitMQPublisher;
use App\Infrastructure\Messaging\RabbitMQService;
use App\Infrastructure\Repositories\ProductionOrderMongoRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RabbitMQService::class, fn () => RabbitMQService::fromEnv());
        $this->app->bind(MessagePublisherInterface::class, ProductionRabbitMQPublisher::class);
        $this->app->bind(
            ProductionOrderRepositoryInterface::class,
            ProductionOrderMongoRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
