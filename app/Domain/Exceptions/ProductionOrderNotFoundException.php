<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use DomainException;

final class ProductionOrderNotFoundException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("Production order with ID '{$id}' not found.");
    }

    public static function forWorkOrder(int $workOrderId): self
    {
        return new self("Production order for Work Order #{$workOrderId} not found.");
    }
}