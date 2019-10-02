<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model;

use RuntimeException;

final class CouldNotRescheduleConcert extends RuntimeException
{
    public static function becauseItWasAlreadyCancelled(): self
    {
        return new self(
            'Could not reschedule this concert because it was already cancelled'
        );
    }
}
