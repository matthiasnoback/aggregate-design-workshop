<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use RuntimeException;

final class CouldNotFindReservation extends RuntimeException
{
    public static function withId(ReservationId $reservationId): self
    {
        return new self(
            'Could not find a reservation with ID ' . $reservationId->asString()
        );
    }
}
