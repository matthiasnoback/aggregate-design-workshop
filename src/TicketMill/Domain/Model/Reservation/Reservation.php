<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Reservation;

use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Concert\ConcertId;
use TicketMill\Domain\Model\Reservation\ReservationId;

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

    private function __construct(
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

    public static function make(
        ReservationId $reservationId,
        ConcertId $concertId,
        EmailAddress $emailAddress,
        int $numberOfSeats
    ): Reservation {
        return new self(
            $reservationId,
            $concertId,
            $emailAddress,
            $numberOfSeats
        );
    }

    public function reservationId(): ReservationId
    {
        return $this->reservationId;
    }
}
