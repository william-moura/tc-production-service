<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTOs\ProductionOrderOutputDto;
use App\Application\Services\MessagePublisherInterface;
use App\Domain\Exceptions\ProductionOrderNotFoundException;
use App\Domain\Repositories\ProductionOrderRepositoryInterface;

final class CompleteRepairUseCase
{
    public function __construct(
        private readonly ProductionOrderRepositoryInterface $repository,
        private readonly MessagePublisherInterface $publisher
    ) {}

    public function execute(int $workOrderId): ProductionOrderOutputDto
    {
        $order = $this->repository->findByWorkOrderId($workOrderId);

        if ($order === null) {
            throw ProductionOrderNotFoundException::forWorkOrder($workOrderId);
        }

        $order->complete();
        $saved = $this->repository->save($order);

        // Dispara o evento de sucesso para o tc-os-service finalizar a OS
        $this->publisher->publish('queue.repair.completed', [
            'event' => 'repair.completed',
            'work_order_id' => $saved->getWorkOrderId()->getValue(),
            'mechanic_id' => $saved->getMechanicId(),
            'finished_at' => $saved->getFinishedAt()?->format(DATE_ATOM),
        ]);

        return ProductionOrderOutputDto::fromDomain($saved);
    }
}