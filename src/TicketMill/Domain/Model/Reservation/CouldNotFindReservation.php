<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Reservation;

use RuntimeException;
use TicketMill\Domain\Model\Reservation\ReservationId;

final class CouldNotFindReservation extends RuntimeException
{
    public static function withId(ReservationId $reservationId): self
    {
        return new self(
            'Could not find a reservation with ID ' . $reservationId->asString()
        );
    }
}
