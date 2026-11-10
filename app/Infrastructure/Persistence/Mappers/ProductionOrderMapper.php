<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mappers;

use App\Domain\Entities\ProductionOrder;
use App\Domain\ValueObjects\ProductionStatus;
use App\Domain\ValueObjects\WorkOrderId;
use App\Infrastructure\Persistence\Models\ProductionOrderMongoModel;
use DateTimeImmutable;
use MongoDB\BSON\UTCDateTime;

final class ProductionOrderMapper
{
    /**
     * Mapeia do Model do MongoDB para a Entidade de Domínio.
     */
    public static function toDomain(ProductionOrderMongoModel $model): ProductionOrder
    {
        return new ProductionOrder(
            id: (string) $model->_id,
            workOrderId: new WorkOrderId((int) $model->work_order_id),
            customerId: (int) $model->customer_id,
            status: ProductionStatus::from((string) $model->status),
            mechanicId: $model->mechanic_id !== null ? (int) $model->mechanic_id : null,
            cancellationReason: $model->cancellation_reason,
            receivedAt: self::parseDateTime($model->received_at) ?? new DateTimeImmutable(),
            startedAt: self::parseDateTime($model->started_at),
            finishedAt: self::parseDateTime($model->finished_at)
        );
    }

    /**
     * Converte a Entidade de Domínio em array estruturado para persistência no MongoDB.
     *
     * @return array<string, mixed>
     */
    public static function toDocument(ProductionOrder $order): array
    {
        return [
            'work_order_id' => $order->getWorkOrderId()->getValue(),
            'customer_id' => $order->getCustomerId(),
            'status' => $order->getStatus()->value,
            'mechanic_id' => $order->getMechanicId(),
            'cancellation_reason' => $order->getCancellationReason(),
            'received_at' => self::toUtcDateTime($order->getReceivedAt()),
            'started_at' => self::toUtcDateTime($order->getStartedAt()),
            'finished_at' => self::toUtcDateTime($order->getFinishedAt()),
        ];
    }

    private static function parseDateTime(mixed $value): ?DateTimeImmutable
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof UTCDateTime) {
            return DateTimeImmutable::createFromMutable($value->toDateTime());
        }

        if ($value instanceof DateTimeImmutable) {
            return $value;
        }

        if (is_string($value)) {
            return new DateTimeImmutable($value);
        }

        return null;
    }

    private static function toUtcDateTime(?DateTimeImmutable $dateTime): ?UTCDateTime
    {
        if ($dateTime === null) {
            return null;
        }

        return new UTCDateTime($dateTime->getTimestamp() * 1000);
    }
}