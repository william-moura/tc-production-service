<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\ProductionOrder;
use App\Domain\Repositories\ProductionOrderRepositoryInterface;
use App\Infrastructure\Persistence\Mappers\ProductionOrderMapper;
use App\Infrastructure\Persistence\Models\ProductionOrderMongoModel;

final class ProductionOrderMongoRepository implements ProductionOrderRepositoryInterface
{
    public function save(ProductionOrder $order): ProductionOrder
    {
        $documentData = ProductionOrderMapper::toDocument($order);

        if ($order->getId() !== null) {
            $model = ProductionOrderMongoModel::find($order->getId());

            if ($model !== null) {
                $model->update($documentData);
                return ProductionOrderMapper::toDomain($model->fresh());
            }
        }

        /** @var ProductionOrderMongoModel $model */
        $model = ProductionOrderMongoModel::create($documentData);

        return ProductionOrderMapper::toDomain($model);
    }

    public function findById(string $id): ?ProductionOrder
    {
        /** @var ProductionOrderMongoModel|null $model */
        $model = ProductionOrderMongoModel::find($id);

        return $model !== null ? ProductionOrderMapper::toDomain($model) : null;
    }

    public function findByWorkOrderId(int $workOrderId): ?ProductionOrder
    {
        /** @var ProductionOrderMongoModel|null $model */
        $model = ProductionOrderMongoModel::where('work_order_id', $workOrderId)->first();

        return $model !== null ? ProductionOrderMapper::toDomain($model) : null;
    }

    /**
     * @return ProductionOrder[]
     */
    public function findByStatus(string $status): array
    {
        return ProductionOrderMongoModel::where('status', $status)
            ->get()
            ->map(fn(ProductionOrderMongoModel $model) => ProductionOrderMapper::toDomain($model))
            ->all();
    }

    /**
     * @return ProductionOrder[]
     */
    public function findAll(): array
    {
        return ProductionOrderMongoModel::orderBy('received_at', 'desc')
            ->get()
            ->map(fn(ProductionOrderMongoModel $model) => ProductionOrderMapper::toDomain($model))
            ->all();
    }
}