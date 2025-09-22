<?php

declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

final readonly class ConcertWasPlanned
{
    public function __construct(private ConcertId $concertId, private int $numberOfSeats)
    {
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
