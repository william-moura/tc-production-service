<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Exceptions\ProductionOrderNotFoundException;
use PHPUnit\Framework\TestCase;

final class ProductionOrderNotFoundExceptionTest extends TestCase
{
    public function test_should_instantiate_via_for_id(): void
    {
        $exception = ProductionOrderNotFoundException::forId('64f1a2b3c4d5e6f7a8b9c0d1');

        $this->assertSame("Production order with ID '64f1a2b3c4d5e6f7a8b9c0d1' not found.", $exception->getMessage());
    }

    public function test_should_instantiate_via_for_work_order(): void
    {
        $exception = ProductionOrderNotFoundException::forWorkOrder(105);

        $this->assertSame('Production order for Work Order #105 not found.', $exception->getMessage());
    }
}