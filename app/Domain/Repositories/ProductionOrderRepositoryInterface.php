<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\ProductionOrder;

interface ProductionOrderRepositoryInterface
{
    public function save(ProductionOrder $order): ProductionOrder;
    public function findById(string $id): ?ProductionOrder;
    public function findByWorkOrderId(int $workOrderId): ?ProductionOrder;
    /** @return ProductionOrder[] */
    public function findByStatus(string $status): array;
    /** @return ProductionOrder[] */
    public function findAll(): array;
}