<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\ProductionOrderOutputDto;
use App\Application\Services\MessagePublisherInterface;
use App\Domain\Exceptions\ProductionOrderNotFoundException;
use App\Domain\Repositories\ProductionOrderRepositoryInterface;

final class AbortProductionUseCase
{
    public function __construct(
        private readonly ProductionOrderRepositoryInterface $repository,
        private readonly MessagePublisherInterface $publisher
    ) {}

    public function execute(int $workOrderId, string $reason): ProductionOrderOutputDto
    {
        $order = $this->repository->findByWorkOrderId($workOrderId);

        if ($order === null) {
            throw ProductionOrderNotFoundException::forWorkOrder($workOrderId);
        }

        $order->abort($reason);
        $saved = $this->repository->save($order);

        // Dispara evento de compensação para o tc-billing-service estornar o pagamento
        $this->publisher->publish('queue.production.aborted', [
            'event' => 'production.aborted',
            'work_order_id' => $saved->getWorkOrderId()->getValue(),
            'reason' => $reason,
        ]);

        return ProductionOrderOutputDto::fromDomain($saved);
    }
}