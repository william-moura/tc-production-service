<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure;

use App\Infrastructure\Messaging\Publishers\ProductionRabbitMQPublisher;
use App\Infrastructure\Messaging\RabbitMQService;
use PHPUnit\Framework\TestCase;

final class ProductionRabbitMQPublisherTest extends TestCase
{
    public function test_should_delegate_publishing_to_rabbitmq_service(): void
    {
        $rabbitMQService = $this->createMock(RabbitMQService::class);

        $payload = [
            'event' => 'repair.completed',
            'work_order_id' => 101,
            'mechanic_id' => 7,
        ];

        $rabbitMQService->expects($this->once())
            ->method('publish')
            ->with('queue.repair.completed', $payload);

        $publisher = new ProductionRabbitMQPublisher($rabbitMQService);
        $publisher->publish('queue.repair.completed', $payload);
    }
}