<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Reservation;

use TicketMill\Domain\Model\Common\EmailAddress;

final class ReservationWasConfirmed
{
    private EmailAddress $emailAddress;
    private int $numberOfSeats;

    public function __construct(EmailAddress $emailAddress, int $numberOfSeats)
    {
        $this->emailAddress = $emailAddress;
        $this->numberOfSeats = $numberOfSeats;
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
