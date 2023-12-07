<?php

namespace TicketMill\Domain\Model\Concert;

final class ConcertWasCancelled
{
    private ConcertId $concertId;

    public function __construct(
        ConcertId $concertId
    )
    {
        $this->concertId = $concertId;
    }

    public function getConcertId(): ConcertId
    {
        return $this->concertId;
    }
}
