<?php

declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use TicketMill\Domain\Model\Common\EmailAddress;

final readonly class Reservation
{
    public function __construct(
        private ReservationId $reservationId,
        private EmailAddress $emailAddress,
        private int $numberOfSeats
    ) {
    }

    public function reservationId(): ReservationId
    {
        return $this->reservationId;
    }

    public function numberOfSeats(): int
    {
        return $this->numberOfSeats;
    }
}
