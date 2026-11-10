<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure;

use App\Infrastructure\Persistence\Models\ProductionOrderMongoModel;
use PHPUnit\Framework\TestCase;

final class ProductionOrderMongoModelTest extends TestCase
{
    public function test_should_have_correct_collection_and_connection_configurations(): void
    {
        $model = new ProductionOrderMongoModel();

        $this->assertSame('mongodb', $model->getConnectionName());
        $this->assertSame('production_orders', $model->getTable());
        $this->assertSame('_id', $model->getKeyName());
        $this->assertFalse($model->getIncrementing());
    }

    public function test_should_contain_expected_fillable_attributes(): void
    {
        $model = new ProductionOrderMongoModel();

        $expectedFillable = [
            'work_order_id',
            'customer_id',
            'status',
            'mechanic_id',
            'cancellation_reason',
            'received_at',
            'started_at',
            'finished_at',
        ];

        $this->assertSame($expectedFillable, $model->getFillable());
    }
}