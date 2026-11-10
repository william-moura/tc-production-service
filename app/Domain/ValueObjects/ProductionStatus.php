<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

enum ProductionStatus: string
{
    case RECEIVED = 'RECEIVED';
    case IN_PRODUCTION = 'IN_PRODUCTION';
    case COMPLETED = 'COMPLETED';
    case ABORTED = 'ABORTED';

    public function isFinal(): bool
    {
        return in_array($this, [self::COMPLETED, self::ABORTED], true);
    }
}