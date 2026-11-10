<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Events\RepairCompletedDomainEvent;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class RepairCompletedDomainEventTest extends TestCase
{
    public function test_should_instantiate_and_format_payload_correctly(): void
    {
        $timestamp = new DateTimeImmutable('2026-11-10T16:00:00+00:00');
        $event = new RepairCompletedDomainEvent(
            workOrderId: 105,
            mechanicId: 8,
            occurredOn: $timestamp
        );

        $payload = $event->toPayload();

        $this->assertSame(105, $event->workOrderId);
        $this->assertSame(8, $event->mechanicId);
        $this->assertSame('repair.completed', $payload['event']);
        $this->assertSame(105, $payload['work_order_id']);
        $this->assertSame(8, $payload['mechanic_id']);
        $this->assertSame('2026-11-10T16:00:00+00:00', $payload['occurred_on']);
    }
}