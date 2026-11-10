<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure;

use App\Domain\Entities\ProductionOrder;
use App\Domain\Repositories\ProductionOrderRepositoryInterface;
use App\Infrastructure\Repositories\ProductionOrderMongoRepository;
use PHPUnit\Framework\TestCase;

final class ProductionOrderMongoRepositoryTest extends TestCase
{
    public function test_should_implement_production_order_repository_interface(): void
    {
        $repository = new ProductionOrderMongoRepository();

        $this->assertInstanceOf(ProductionOrderRepositoryInterface::class, $repository);
    }
}