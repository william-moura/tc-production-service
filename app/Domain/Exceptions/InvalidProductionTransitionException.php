<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use DomainException;

final class InvalidProductionTransitionException extends DomainException
{
    public static function invalidTransition(string $from, string $to): self
    {
        return new self("Cannot transition production order from '{$from}' to '{$to}'.");
    }

    public static function orderAlreadyFinished(string $status): self
    {
        return new self("Cannot update order because it is already in final state: '{$status}'.");
    }
}