<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Events\ProductionAbortedDomainEvent;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class ProductionAbortedDomainEventTest extends TestCase
{
    public function test_should_instantiate_and_format_compensation_payload(): void
    {
        $timestamp = new DateTimeImmutable('2026-11-10T16:15:00+00:00');
        $event = new ProductionAbortedDomainEvent(
            workOrderId: 204,
            reason: 'Engine block damaged beyond repair',
            occurredOn: $timestamp
        );

        $payload = $event->toPayload();

        $this->assertSame(204, $event->workOrderId);
        $this->assertSame('Engine block damaged beyond repair', $event->reason);
        $this->assertSame('production.aborted', $payload['event']);
        $this->assertSame(204, $payload['work_order_id']);
        $this->assertSame('Engine block damaged beyond repair', $payload['reason']);
        $this->assertSame('2026-11-10T16:15:00+00:00', $payload['occurred_on']);
    }
}