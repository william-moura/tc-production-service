<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\ValueObjects\MechanicId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class MechanicIdTest extends TestCase
{
    public function test_should_create_with_positive_integer(): void
    {
        $mechanicId = new MechanicId(15);

        $this->assertSame(15, $mechanicId->getValue());
        $this->assertSame('15', (string) $mechanicId);
    }

    public function test_should_throw_exception_when_id_is_zero_or_negative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Mechanic ID must be a positive integer.');

        new MechanicId(0);
    }

    public function test_should_check_equality(): void
    {
        $id1 = new MechanicId(10);
        $id2 = new MechanicId(10);
        $id3 = new MechanicId(20);

        $this->assertTrue($id1->equals($id2));
        $this->assertFalse($id1->equals($id3));
    }
}