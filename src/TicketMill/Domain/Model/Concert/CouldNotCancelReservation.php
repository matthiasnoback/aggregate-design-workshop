<?php

namespace TicketMill\Domain\Model\Concert;

class CouldNotCancelReservation extends \RuntimeException
{
    public static function becauseItWasNotFound(ReservationId $reservationId): self
    {
        return new self(
            sprintf('Could not find reservation %s', $reservationId->asString())
        );
    }
}
