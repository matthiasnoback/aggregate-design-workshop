<?php

namespace TicketMill\Domain\Model\Concert;

use TicketMill\Domain\Model\Reservation\ReservationId;

class ReservationWasAccepted
{
    private ReservationId $reservationId;

    public function __construct(ReservationId $reservationId)
    {
        $this->reservationId = $reservationId;
    }

    public function getReservationId(): ReservationId
    {
        return $this->reservationId;
    }
}
