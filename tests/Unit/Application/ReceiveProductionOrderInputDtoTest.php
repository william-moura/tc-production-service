<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use App\Application\DTOs\ReceiveProductionOrderInputDto;
use PHPUnit\Framework\TestCase;

final class ReceiveProductionOrderInputDtoTest extends TestCase
{
    public function test_should_instantiate_via_constructor(): void
    {
        $dto = new ReceiveProductionOrderInputDto(workOrderId: 10, customerId: 3);

        $this->assertSame(10, $dto->workOrderId);
        $this->assertSame(3, $dto->customerId);
    }

    public function test_should_instantiate_from_payload(): void
    {
        $payload = [
            'work_order_id' => '101',
            'customer_id' => '42',
        ];

        $dto = ReceiveProductionOrderInputDto::fromPayload($payload);

        $this->assertSame(101, $dto->workOrderId);
        $this->assertSame(42, $dto->customerId);
        $this->assertSame([
            'work_order_id' => 101,
            'customer_id' => 42,
        ], $dto->toArray());
    }
}