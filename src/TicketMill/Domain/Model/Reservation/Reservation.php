<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Reservation;

use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Concert\ConcertId;

/**
 * @deprecated Only use this when you arrived at Assignment 5
 */
final class Reservation
{
    /**
     * @var ReservationId
     */
    private $reservationId;

    /**
     * @var ConcertId
     */
    private $concertId;

    /**
     * @var EmailAddress
     */
    private $emailAddress;

    /**
     * @var int
     */
    private $numberOfSeats;

    private function __construct()
    {
    }

    public static function make(
        ReservationId $reservationId,
        ConcertId $concertId,
        EmailAddress $emailAddress,
        int $numberOfSeats
    ): Reservation {
        $reservation = new self();

        $reservation->reservationId = $reservationId;
        $reservation->concertId = $concertId;
        $reservation->emailAddress = $emailAddress;
        $reservation->numberOfSeats = $numberOfSeats;

        return $reservation;
    }

    public function reservationId(): ReservationId
    {
        return $this->reservationId;
    }
}
