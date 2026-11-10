<?php

declare(strict_types=1);

namespace Tests\Unit\Presentation;

use App\Application\DTOs\ProductionOrderOutputDto;
use App\Application\UseCases\CompleteRepairUseCase;
use App\Domain\Entities\ProductionOrder;
use App\Domain\Exceptions\ProductionOrderNotFoundException;
use App\Domain\Repositories\ProductionOrderRepositoryInterface;
use App\Domain\ValueObjects\ProductionStatus;
use App\Domain\ValueObjects\WorkOrderId;
use App\Presentation\Http\Controllers\ProductionOrderController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

final class ProductionOrderControllerTest extends TestCase
{
    public function test_show_should_return_order_when_found(): void
    {
        $order = new ProductionOrder(
            id: '64f1a2b3c4d5e6f7a8b9c0d1',
            workOrderId: new WorkOrderId(50),
            customerId: 3,
            status: ProductionStatus::RECEIVED
        );

        $repository = $this->createMock(ProductionOrderRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findById')
            ->with('64f1a2b3c4d5e6f7a8b9c0d1')
            ->willReturn($order);

        $controller = new ProductionOrderController($repository);
        $response = $controller->show('64f1a2b3c4d5e6f7a8b9c0d1');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $payload = $response->getData(true)['data'];
        $this->assertSame(50, $payload['work_order_id']);
        $this->assertSame('RECEIVED', $payload['status']);
    }

    public function test_show_should_throw_exception_when_order_not_found(): void
    {
        $this->expectException(ProductionOrderNotFoundException::class);

        $repository = $this->createMock(ProductionOrderRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findById')
            ->with('non-existent-id')
            ->willReturn(null);

        $controller = new ProductionOrderController($repository);
        $controller->show('non-existent-id');
    }

    public function test_complete_should_call_use_case_and_return_response(): void
    {
        $repository = $this->createMock(ProductionOrderRepositoryInterface::class);
        $useCase = $this->createMock(CompleteRepairUseCase::class);

        $outputDto = new ProductionOrderOutputDto(
            id: '64f1a2b3c4d5e6f7a8b9c0d1',
            workOrderId: 50,
            customerId: 3,
            status: 'COMPLETED',
            mechanicId: 5,
            cancellationReason: null,
            receivedAt: '2026-11-10T16:00:00+00:00',
            startedAt: '2026-11-10T16:10:00+00:00',
            finishedAt: '2026-11-10T17:00:00+00:00'
        );

        $useCase->expects($this->once())
            ->method('execute')
            ->with(50)
            ->willReturn($outputDto);

        $controller = new ProductionOrderController($repository);
        $response = $controller->complete(50, $useCase);

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $payload = $response->getData(true);
        $this->assertSame('Repair completed successfully.', $payload['message']);
        $this->assertSame('COMPLETED', $payload['data']['status']);
    }
}