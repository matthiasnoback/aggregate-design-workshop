<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use TicketMill\Domain\Model\Common\EmailAddress;

final class Reservation
{
    /**
     * @var ReservationId
     */
    private $reservationId;

    /**
     * @var EmailAddress
     */
    private $emailAddress;

    /**
     * @var int
     */
    private $numberOfSeats;

    public function __construct(
        ReservationId $reservationId,
        EmailAddress $emailAddress,
        int $numberOfSeats
    ) {
        $this->reservationId = $reservationId;
        $this->emailAddress = $emailAddress;
        $this->numberOfSeats = $numberOfSeats;
    }

    public function numberOfSeats(): int
    {
        return $this->numberOfSeats;
    }
}
