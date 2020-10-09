<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Reservation;

use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Concert\ConcertId;

final class ReservationWasMade
{
    private ReservationId $reservationId;
    private ConcertId $concertId;
    private EmailAddress $emailAddress;
    private int $numberOfSeats;

    public function __construct(
        ReservationId $reservationId,
        ConcertId $concertId,
        EmailAddress $emailAddress,
        int $numberOfSeats
    ) {
        $this->reservationId = $reservationId;
        $this->concertId = $concertId;
        $this->emailAddress = $emailAddress;
        $this->numberOfSeats = $numberOfSeats;
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
