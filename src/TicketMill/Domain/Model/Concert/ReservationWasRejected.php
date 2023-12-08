<?php

namespace TicketMill\Domain\Model\Concert;

use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Reservation\ReservationId;

class ReservationWasRejected
{
    private ReservationId $reservationId;
    private EmailAddress $emailAddress;

    public function __construct(ReservationId $reservationId, EmailAddress $emailAddress)
    {
        $this->reservationId = $reservationId;
        $this->emailAddress = $emailAddress;
    }

    public function getReservationId(): ReservationId
    {
        return $this->reservationId;
    }

    public function getEmailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }
}
