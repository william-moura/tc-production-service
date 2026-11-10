<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\ProductionOrderOutputDto;
use App\Domain\Entities\ProductionOrder;
use App\Domain\Repositories\ProductionOrderRepositoryInterface;

final class ReceiveProductionOrderUseCase
{
    public function __construct(
        private readonly ProductionOrderRepositoryInterface $repository
    ) {}

    public function execute(int $workOrderId, int $customerId): ProductionOrderOutputDto
    {
        $existing = $this->repository->findByWorkOrderId($workOrderId);
        if ($existing !== null) {
            return ProductionOrderOutputDto::fromDomain($existing);
        }

        $order = ProductionOrder::receive($workOrderId, $customerId);
        $saved = $this->repository->save($order);

        return ProductionOrderOutputDto::fromDomain($saved);
    }
}