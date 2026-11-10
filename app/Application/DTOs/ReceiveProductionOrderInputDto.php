<?php

declare(strict_types=1);

namespace App\Application\DTOs;

final class ReceiveProductionOrderInputDto
{
    public function __construct(
        public readonly int $workOrderId,
        public readonly int $customerId
    ) {}

    /**
     * Instancia o DTO a partir do payload recebido da fila RabbitMQ (queue.payment.approved).
     *
     * @param array{work_order_id: int|numeric, customer_id: int|numeric} $payload
     */
    public static function fromPayload(array $payload): self
    {
        return new self(
            workOrderId: (int) $payload['work_order_id'],
            customerId: (int) $payload['customer_id']
        );
    }

    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return [
            'work_order_id' => $this->workOrderId,
            'customer_id' => $this->customerId,
        ];
    }
}