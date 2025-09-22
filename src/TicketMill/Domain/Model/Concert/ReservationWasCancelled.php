<?php

declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

final readonly class ReservationWasCancelled
{
    public function __construct(private ReservationId $reservationId, private ConcertId $concertId, private int $numberOfSeats)
    {
    }

    public function reservationId(): ReservationId
    {
        return $this->reservationId;
    }

    public function concertId(): ConcertId
    {
        return $this->concertId;
    }

    public function numberOfSeats(): int
    {
        return $this->numberOfSeats;
    }
}
