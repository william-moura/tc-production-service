<?php

declare(strict_types=1);

namespace App\Application\DTOs;

final class AbortProductionInputDto
{
    public function __construct(
        public readonly int $workOrderId,
        public readonly string $reason
    ) {}

    /**
     * Instancia o DTO a partir de um array associativo (ex: vindo da requisição HTTP ou evento).
     *
     * @param array{work_order_id: int|numeric, reason: string} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            workOrderId: (int) $data['work_order_id'],
            reason: trim((string) $data['reason'])
        );
    }

    /**
     * Converte o DTO para array associativo.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'work_order_id' => $this->workOrderId,
            'reason' => $this->reason,
        ];
    }
}