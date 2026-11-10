<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\ProductionOrderOutputDto;
use App\Application\DTOs\StartRepairInputDto;
use App\Domain\Exceptions\ProductionOrderNotFoundException;
use App\Domain\Repositories\ProductionOrderRepositoryInterface;

final class StartRepairUseCase
{
    public function __construct(
        private readonly ProductionOrderRepositoryInterface $repository
    ) {}

    public function execute(StartRepairInputDto $dto): ProductionOrderOutputDto
    {
        $order = $this->repository->findByWorkOrderId($dto->workOrderId);

        if ($order === null) {
            throw ProductionOrderNotFoundException::forWorkOrder($dto->workOrderId);
        }

        $order->startRepair($dto->mechanicId);

        $savedOrder = $this->repository->save($order);

        return ProductionOrderOutputDto::fromDomain($savedOrder);
    }
}