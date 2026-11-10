<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

final class MechanicId
{
    public function __construct(
        private readonly int $value
    ) {
        if ($this->value <= 0) {
            throw new InvalidArgumentException('Mechanic ID must be a positive integer.');
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->getValue();
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}