<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Reservation;

use TicketMill\Domain\Model\Concert\ConcertId;

final class ReservationWasCancelled
{
    /**
     * @var ConcertId
     */
    private $concertId;

    /**
     * @var int
     */
    private $numberOfSeats;

    public function __construct(
        ConcertId $concertId,
        int $numberOfSeats
    ) {
        $this->concertId = $concertId;
        $this->numberOfSeats = $numberOfSeats;
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
