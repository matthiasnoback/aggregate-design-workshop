<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

final class ConcertWasPlanned
{
    private ConcertId $concertId;

    private int $numberOfSeats;

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
