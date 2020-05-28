<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

final class ConcertWasCancelled
{
    /**
     * @var ConcertId
     */
    private $concertId;

    public function __construct(ConcertId $concertId)
    {
        $this->concertId = $concertId;
    }
}
