<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Reservation;

use TicketMill\Domain\Model\Concert\ConcertId;

final class ReservationWasCancelled
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
     * @var int
     */
    private $numberOfSeats;

    public function __construct(
        ReservationId $reservationId,
        ConcertId $concertId,
        int $numberOfSeats
    ) {
        $this->reservationId = $reservationId;
        $this->concertId = $concertId;
        $this->numberOfSeats = $numberOfSeats;
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
