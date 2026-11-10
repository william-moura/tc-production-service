<?php

declare(strict_types=1);

namespace App\Application\DTOs;

use App\Domain\Entities\ProductionOrder;

final class ProductionOrderOutputDto
{
    public function __construct(
        public readonly ?string $id,
        public readonly int $workOrderId,
        public readonly int $customerId,
        public readonly string $status,
        public readonly ?int $mechanicId,
        public readonly ?string $cancellationReason,
        public readonly string $receivedAt,
        public readonly ?string $startedAt,
        public readonly ?string $finishedAt
    ) {}

    public static function fromDomain(ProductionOrder $order): self
    {
        return new self(
            id: $order->getId(),
            workOrderId: $order->getWorkOrderId()->getValue(),
            customerId: $order->getCustomerId(),
            status: $order->getStatus()->value,
            mechanicId: $order->getMechanicId(),
            cancellationReason: $order->getCancellationReason(),
            receivedAt: $order->getReceivedAt()->format(DATE_ATOM),
            startedAt: $order->getStartedAt()?->format(DATE_ATOM),
            finishedAt: $order->getFinishedAt()?->format(DATE_ATOM)
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'work_order_id' => $this->workOrderId,
            'customer_id' => $this->customerId,
            'status' => $this->status,
            'mechanic_id' => $this->mechanicId,
            'cancellation_reason' => $this->cancellationReason,
            'received_at' => $this->receivedAt,
            'started_at' => $this->startedAt,
            'finished_at' => $this->finishedAt,
        ];
    }
}