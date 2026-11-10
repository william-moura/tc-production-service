<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use App\Application\DTOs\StartRepairInputDto;
use PHPUnit\Framework\TestCase;

final class StartRepairInputDtoTest extends TestCase
{
    public function test_should_instantiate_via_constructor(): void
    {
        $dto = new StartRepairInputDto(workOrderId: 45, mechanicId: 7);

        $this->assertSame(45, $dto->workOrderId);
        $this->assertSame(7, $dto->mechanicId);
    }

    public function test_should_instantiate_from_request_data(): void
    {
        $dto = StartRepairInputDto::fromRequest(101, [
            'mechanic_id' => '12',
        ]);

        $this->assertSame(101, $dto->workOrderId);
        $this->assertSame(12, $dto->mechanicId);
        $this->assertSame([
            'work_order_id' => 101,
            'mechanic_id' => 12,
        ], $dto->toArray());
    }
}