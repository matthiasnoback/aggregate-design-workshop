<?php

declare(strict_types=1);

namespace TicketMill\Domain\Model\Reservation;

use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Common\EventRecording;
use TicketMill\Domain\Model\Concert\ConcertId;
use TicketMill\Domain\Model\Concert\ReservationId;

/**
 * @deprecated Only use this when you arrived at Assignment 5
 */
final class Reservation
{
    use EventRecording;

    private ReservationId $reservationId;

    private ConcertId $concertId;

    private EmailAddress $emailAddress;

    private int $numberOfSeats;

    private function __construct(
    ) {
    }

    public static function make(
        ReservationId $reservationId,
        ConcertId $concertId,
        EmailAddress $emailAddress,
        int $numberOfSeats
    ): self {
        $instance = new self();

        $instance->reservationId = $reservationId;
        $instance->concertId = $concertId;
        $instance->emailAddress = $emailAddress;
        $instance->numberOfSeats = $numberOfSeats;

        return $instance;
    }

    public function reservationId(): ReservationId
    {
        return $this->reservationId;
    }

    public function cancel(): void
    {
    }
}
