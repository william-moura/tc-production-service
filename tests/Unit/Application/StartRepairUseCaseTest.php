<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use App\Application\DTOs\StartRepairInputDto;
use App\Application\UseCases\StartRepairUseCase;
use App\Domain\Entities\ProductionOrder;
use App\Domain\Exceptions\InvalidProductionTransitionException;
use App\Domain\Exceptions\ProductionOrderNotFoundException;
use App\Domain\Repositories\ProductionOrderRepositoryInterface;
use App\Domain\ValueObjects\ProductionStatus;
use PHPUnit\Framework\TestCase;

final class StartRepairUseCaseTest extends TestCase
{
    public function test_should_start_repair_and_assign_mechanic_successfully(): void
    {
        $order = ProductionOrder::receive(workOrderId: 101, customerId: 5);

        $repository = $this->createMock(ProductionOrderRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findByWorkOrderId')
            ->with(101)
            ->willReturn($order);

        $repository->expects($this->once())
            ->method('save')
            ->willReturnCallback(fn(ProductionOrder $saved) => $saved);

        $useCase = new StartRepairUseCase($repository);
        $dto = new StartRepairInputDto(workOrderId: 101, mechanicId: 12);

        $output = $useCase->execute($dto);

        $this->assertSame(101, $output->workOrderId);
        $this->assertSame(ProductionStatus::IN_PRODUCTION->value, $output->status);
        $this->assertSame(12, $output->mechanicId);
        $this->assertNotNull($output->startedAt);
    }

    public function test_should_throw_exception_when_production_order_not_found(): void
    {
        $this->expectException(ProductionOrderNotFoundException::class);

        $repository = $this->createMock(ProductionOrderRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findByWorkOrderId')
            ->with(999)
            ->willReturn(null);

        $useCase = new StartRepairUseCase($repository);
        $dto = new StartRepairInputDto(workOrderId: 999, mechanicId: 12);

        $useCase->execute($dto);
    }

    public function test_should_throw_exception_when_transitioning_from_invalid_state(): void
    {
        $this->expectException(InvalidProductionTransitionException::class);

        $order = ProductionOrder::receive(workOrderId: 102, customerId: 5);
        $order->startRepair(12); // Já está IN_PRODUCTION

        $repository = $this->createMock(ProductionOrderRepositoryInterface::class);
        $repository->method('findByWorkOrderId')->willReturn($order);

        $useCase = new StartRepairUseCase($repository);
        $dto = new StartRepairInputDto(workOrderId: 102, mechanicId: 15);

        $useCase->execute($dto);
    }
}