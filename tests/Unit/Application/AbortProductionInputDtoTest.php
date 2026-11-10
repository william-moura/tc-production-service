<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use App\Application\DTOs\AbortProductionInputDto;
use PHPUnit\Framework\TestCase;

final class AbortProductionInputDtoTest extends TestCase
{
    public function test_should_instantiate_from_constructor(): void
    {
        $dto = new AbortProductionInputDto(workOrderId: 10, reason: 'Parts unavailable');

        $this->assertSame(10, $dto->workOrderId);
        $this->assertSame('Parts unavailable', $dto->reason);
    }

    public function test_should_instantiate_from_array(): void
    {
        $payload = [
            'work_order_id' => '25',
            'reason' => '  Mechanic strike  ',
        ];

        $dto = AbortProductionInputDto::fromArray($payload);

        $this->assertSame(25, $dto->workOrderId);
        $this->assertSame('Mechanic strike', $dto->reason);
        $this->assertSame([
            'work_order_id' => 25,
            'reason' => 'Mechanic strike',
        ], $dto->toArray());
    }
}