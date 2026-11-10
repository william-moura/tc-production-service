<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Entities\ProductionOrder;
use App\Domain\Exceptions\InvalidProductionTransitionException;
use App\Domain\ValueObjects\ProductionStatus;
use PHPUnit\Framework\TestCase;

final class ProductionOrderTest extends TestCase
{
    public function test_should_receive_order_with_status_received(): void
    {
        $order = ProductionOrder::receive(workOrderId: 10, customerId: 1);

        $this->assertSame(ProductionStatus::RECEIVED, $order->getStatus());
        $this->assertSame(10, $order->getWorkOrderId()->getValue());
        $this->assertNull($order->getMechanicId());
    }

    public function test_should_transition_from_received_to_in_production(): void
    {
        $order = ProductionOrder::receive(workOrderId: 10, customerId: 1);
        $order->startRepair(mechanicId: 5);

        $this->assertSame(ProductionStatus::IN_PRODUCTION, $order->getStatus());
        $this->assertSame(5, $order->getMechanicId());
        $this->assertNotNull($order->getStartedAt());
    }

    public function test_should_complete_repair_when_in_production(): void
    {
        $order = ProductionOrder::receive(workOrderId: 10, customerId: 1);
        $order->startRepair(mechanicId: 5);
        $order->complete();

        $this->assertSame(ProductionStatus::COMPLETED, $order->getStatus());
        $this->assertNotNull($order->getFinishedAt());
    }

    public function test_should_throw_exception_when_completing_without_starting(): void
    {
        $this->expectException(InvalidProductionTransitionException::class);

        $order = ProductionOrder::receive(workOrderId: 10, customerId: 1);
        $order->complete();
    }

    public function test_should_abort_production_and_set_reason(): void
    {
        $order = ProductionOrder::receive(workOrderId: 10, customerId: 1);
        $order->abort('Missing automotive parts');

        $this->assertSame(ProductionStatus::ABORTED, $order->getStatus());
        $this->assertSame('Missing automotive parts', $order->getCancellationReason());
    }
}