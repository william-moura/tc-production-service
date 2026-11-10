<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Entities\RepairStep;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class RepairStepTest extends TestCase
{
    public function test_should_create_step_uncompleted(): void
    {
        $step = RepairStep::create('Diagnostics', 'Scan engine fault codes');

        $this->assertSame('Diagnostics', $step->getName());
        $this->assertSame('Scan engine fault codes', $step->getDescription());
        $this->assertFalse($step->isCompleted());
        $this->assertNull($step->getCompletedAt());
    }

    public function test_should_mark_step_as_completed(): void
    {
        $step = RepairStep::create('Disassembly');
        $step->complete();

        $this->assertTrue($step->isCompleted());
        $this->assertNotNull($step->getCompletedAt());

        $data = $step->toArray();
        $this->assertSame('Disassembly', $data['name']);
        $this->assertTrue($data['completed']);
        $this->assertNotNull($data['completed_at']);
    }

    public function test_should_throw_exception_when_name_is_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Repair step name cannot be empty.');

        RepairStep::create('   ');
    }
}