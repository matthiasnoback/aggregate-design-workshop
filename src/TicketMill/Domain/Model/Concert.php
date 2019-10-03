<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model;

final class Concert
{
    /**
     * @var ConcertId
     */
    private $concertId;

    public function __construct(ConcertId $concertId)
    {
        $this->concertId = $concertId;
    }

    public function concertId(): ConcertId
    {
        return $this->concertId;
    }
}
