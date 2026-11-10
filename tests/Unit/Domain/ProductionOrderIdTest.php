<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\ValueObjects\ProductionOrderId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ProductionOrderIdTest extends TestCase
{
    public function test_should_accept_valid_mongodb_object_id(): void
    {
        $id = new ProductionOrderId('64f1a2b3c4d5e6f7a8b9c0d1');

        $this->assertSame('64f1a2b3c4d5e6f7a8b9c0d1', $id->getValue());
        $this->assertSame('64f1a2b3c4d5e6f7a8b9c0d1', (string) $id);
    }

    public function test_should_accept_valid_uuid(): void
    {
        $uuid = 'c9bf9e57-1685-4c89-bafb-ff5af830be8a';
        $id = new ProductionOrderId($uuid);

        $this->assertSame($uuid, $id->getValue());
    }

    public function test_should_throw_exception_on_empty_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Production Order ID cannot be empty.');

        new ProductionOrderId('   ');
    }

    public function test_should_throw_exception_on_invalid_format(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid Production Order ID format: 'invalid-id'");

        new ProductionOrderId('invalid-id');
    }

    public function test_should_compare_equality_correctly(): void
    {
        $id1 = new ProductionOrderId('64f1a2b3c4d5e6f7a8b9c0d1');
        $id2 = new ProductionOrderId('64f1a2b3c4d5e6f7a8b9c0d1');
        $id3 = new ProductionOrderId('64f1a2b3c4d5e6f7a8b9c0d2');

        $this->assertTrue($id1->equals($id2));
        $this->assertFalse($id1->equals($id3));
    }
}