<?php

declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use TicketMill\Domain\Model\Common\EmailAddress;

final readonly class ReservationWasMade
{
    public function __construct(private ReservationId $reservationId, private ConcertId $concertId, private EmailAddress $emailAddress, private int $numberOfSeats)
    {
    }

    public function concertId(): ConcertId
    {
        return $this->concertId;
    }

    public function emailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }

    public function numberOfSeats(): int
    {
        return $this->numberOfSeats;
    }
}
