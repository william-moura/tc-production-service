<?php

declare(strict_types=1);

namespace App\Domain\Events;

use DateTimeImmutable;

final class RepairCompletedDomainEvent
{
    public function __construct(
        public readonly int $workOrderId,
        public readonly ?int $mechanicId,
        public readonly DateTimeImmutable $occurredOn = new DateTimeImmutable()
    ) {}

    /**
     * Serializa o evento para publicação no RabbitMQ (queue.repair.completed).
     *
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return [
            'event' => 'repair.completed',
            'work_order_id' => $this->workOrderId,
            'mechanic_id' => $this->mechanicId,
            'occurred_on' => $this->occurredOn->format(DATE_ATOM),
        ];
    }
}