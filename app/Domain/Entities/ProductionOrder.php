<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Exceptions\InvalidProductionTransitionException;
use App\Domain\ValueObjects\ProductionStatus;
use App\Domain\ValueObjects\WorkOrderId;
use DateTimeImmutable;

final class ProductionOrder
{
    public function __construct(
        private ?string $id,
        private readonly WorkOrderId $workOrderId,
        private readonly int $customerId,
        private ProductionStatus $status,
        private ?int $mechanicId = null,
        private ?string $cancellationReason = null,
        private readonly DateTimeImmutable $receivedAt = new DateTimeImmutable(),
        private ?DateTimeImmutable $startedAt = null,
        private ?DateTimeImmutable $finishedAt = null
    ) {}

    public static function receive(int $workOrderId, int $customerId): self
    {
        return new self(
            id: null,
            workOrderId: new WorkOrderId($workOrderId),
            customerId: $customerId,
            status: ProductionStatus::RECEIVED
        );
    }

    public function startRepair(int $mechanicId): void
    {
        if ($this->status !== ProductionStatus::RECEIVED) {
            throw InvalidProductionTransitionException::invalidTransition(
                $this->status->value,
                ProductionStatus::IN_PRODUCTION->value
            );
        }

        $this->mechanicId = $mechanicId;
        $this->status = ProductionStatus::IN_PRODUCTION;
        $this->startedAt = new DateTimeImmutable();
    }

    public function complete(): void
    {
        if ($this->status !== ProductionStatus::IN_PRODUCTION) {
            throw InvalidProductionTransitionException::invalidTransition(
                $this->status->value,
                ProductionStatus::COMPLETED->value
            );
        }

        $this->status = ProductionStatus::COMPLETED;
        $this->finishedAt = new DateTimeImmutable();
    }

    public function abort(string $reason): void
    {
        if ($this->status->isFinal()) {
            throw InvalidProductionTransitionException::orderAlreadyFinished($this->status->value);
        }

        $this->status = ProductionStatus::ABORTED;
        $this->cancellationReason = $reason;
        $this->finishedAt = new DateTimeImmutable();
    }

    public function getId(): ?string { return $this->id; }
    public function getWorkOrderId(): WorkOrderId { return $this->workOrderId; }
    public function getCustomerId(): int { return $this->customerId; }
    public function getStatus(): ProductionStatus { return $this->status; }
    public function getMechanicId(): ?int { return $this->mechanicId; }
    public function getCancellationReason(): ?string { return $this->cancellationReason; }
    public function getReceivedAt(): DateTimeImmutable { return $this->receivedAt; }
    public function getStartedAt(): ?DateTimeImmutable { return $this->startedAt; }
    public function getFinishedAt(): ?DateTimeImmutable { return $this->finishedAt; }
}