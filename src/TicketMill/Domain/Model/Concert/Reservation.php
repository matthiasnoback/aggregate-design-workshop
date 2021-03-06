<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use TicketMill\Domain\Model\Common\EmailAddress;

final class Reservation
{
    private ReservationId $reservationId;
    private EmailAddress $emailAddress;
    private int $numberOfSeats;

    public function __construct(
        ReservationId $reservationId,
        EmailAddress $emailAddress,
        int $numberOfSeats
    ) {
        $this->reservationId = $reservationId;
        $this->emailAddress = $emailAddress;
        $this->numberOfSeats = $numberOfSeats;
    }
}
