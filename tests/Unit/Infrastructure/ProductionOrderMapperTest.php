<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure;

use App\Domain\Entities\ProductionOrder;
use App\Domain\ValueObjects\ProductionStatus;
use App\Domain\ValueObjects\WorkOrderId;
use App\Infrastructure\Persistence\Mappers\ProductionOrderMapper;
use App\Infrastructure\Persistence\Models\ProductionOrderMongoModel;
use DateTimeImmutable;
use MongoDB\BSON\UTCDateTime;
use PHPUnit\Framework\TestCase;

final class ProductionOrderMapperTest extends TestCase
{
    public function test_should_map_domain_entity_to_document_array(): void
    {
        $now = new DateTimeImmutable('2026-11-10T16:00:00+00:00');
        $order = new ProductionOrder(
            id: '64f1a2b3c4d5e6f7a8b9c0d1',
            workOrderId: new WorkOrderId(100),
            customerId: 25,
            status: ProductionStatus::IN_PRODUCTION,
            mechanicId: 7,
            cancellationReason: null,
            receivedAt: $now,
            startedAt: $now,
            finishedAt: null
        );

        $document = ProductionOrderMapper::toDocument($order);

        $this->assertSame(100, $document['work_order_id']);
        $this->assertSame(25, $document['customer_id']);
        $this->assertSame('IN_PRODUCTION', $document['status']);
        $this->assertSame(7, $document['mechanic_id']);
        $this->assertNull($document['cancellation_reason']);
        $this->assertInstanceOf(UTCDateTime::class, $document['received_at']);
        $this->assertInstanceOf(UTCDateTime::class, $document['started_at']);
        $this->assertNull($document['finished_at']);
    }

    public function test_should_map_mongo_model_to_domain_entity(): void
    {
        $model = new ProductionOrderMongoModel();
        $model->_id = '64f1a2b3c4d5e6f7a8b9c0d1';
        $model->work_order_id = 100;
        $model->customer_id = 25;
        $model->status = 'IN_PRODUCTION';
        $model->mechanic_id = 7;
        $model->cancellation_reason = null;
        $model->received_at = new UTCDateTime(1794326400000);
        $model->started_at = new UTCDateTime(1794326400000);
        $model->finished_at = null;

        $entity = ProductionOrderMapper::toDomain($model);

        $this->assertSame('64f1a2b3c4d5e6f7a8b9c0d1', $entity->getId());
        $this->assertSame(100, $entity->getWorkOrderId()->getValue());
        $this->assertSame(25, $entity->getCustomerId());
        $this->assertSame(ProductionStatus::IN_PRODUCTION, $entity->getStatus());
        $this->assertSame(7, $entity->getMechanicId());
        $this->assertNull($entity->getCancellationReason());
        $this->assertNotNull($entity->getStartedAt());
        $this->assertNull($entity->getFinishedAt());
    }
}