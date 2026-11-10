<?php

declare(strict_types=1);

namespace App\Application\DTOs;

final class StartRepairInputDto
{
    public function __construct(
        public readonly int $workOrderId,
        public readonly int $mechanicId
    ) {}

    /**
     * Cria a instância a partir dos parâmetros da requisição HTTP.
     *
     * @param array{work_order_id?: int|numeric, mechanic_id: int|numeric} $data
     */
    public static function fromRequest(int $workOrderId, array $data): self
    {
        return new self(
            workOrderId: $workOrderId,
            mechanicId: (int) $data['mechanic_id']
        );
    }

    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return [
            'work_order_id' => $this->workOrderId,
            'mechanic_id' => $this->mechanicId,
        ];
    }
}