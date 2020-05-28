<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use RuntimeException;

final class CouldNotCancelReservation extends RuntimeException
{
    public static function becauseReservationIsIsUnknown(ReservationId $reservationId): self
    {
        return new self(
            'Unknown reservation ID: ' . $reservationId->asString()
        );
    }
}
