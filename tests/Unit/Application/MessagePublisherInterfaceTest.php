<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use App\Application\Services\MessagePublisherInterface;
use PHPUnit\Framework\TestCase;

final class MessagePublisherInterfaceTest extends TestCase
{
    public function test_can_mock_message_publisher_interface(): void
    {
        $publisher = $this->createMock(MessagePublisherInterface::class);

        $payload = [
            'event' => 'repair.completed',
            'work_order_id' => 101,
            'mechanic_id' => 12,
        ];

        $publisher->expects($this->once())
            ->method('publish')
            ->with('queue.repair.completed', $payload);

        $publisher->publish('queue.repair.completed', $payload);
    }
}