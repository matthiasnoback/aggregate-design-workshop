<?php
declare(strict_types=1);

namespace TicketMill\Application;

use TicketMill\Domain\Model\Concert\ConcertId;

final class AvailableSeats
{
    /**
     * @var ConcertId
     */
    private $concertId;
    /**
     * @var int
     */
    private $numberOfAvailableSeats;

    public function __construct(ConcertId $concertId, int $numberOfAvailableSeats)
    {
        $this->concertId = $concertId;
        $this->numberOfAvailableSeats = $numberOfAvailableSeats;
    }

    public function withNumberOfSeatsReserved(int $numberOfSeats): self
    {
        $copy = clone $this;

        $copy->numberOfAvailableSeats -= $numberOfSeats;

        return $copy;
    }

    public function withNumberOfSeatsCancelled(int $numberOfSeats): self
    {
        $copy = clone $this;

        $copy->numberOfAvailableSeats += $numberOfSeats;

        return $copy;
    }

    public function concertId(): ConcertId
    {
        return $this->concertId;
    }

    public function numberOfAvailableSeats(): int
    {
        return $this->numberOfAvailableSeats;
    }
}
