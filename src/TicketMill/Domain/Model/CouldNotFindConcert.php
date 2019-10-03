<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model;

use RuntimeException;

final class CouldNotFindConcert extends RuntimeException
{
    public static function withId(ConcertId $concertId): self
    {
        return new self(
            'Could not find a concert with ID ' . $concertId->asString()
        );
    }
}
