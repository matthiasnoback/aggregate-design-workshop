<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use TicketMill\Domain\Model\Common\EmailAddress;

final class ReservationWasMade
{
    /**
     * @var ConcertId
     */
    private $concertId;

    /**
     * @var EmailAddress
     */
    private $emailAddress;

    /**
     * @var int
     */
    private $numberOfSeats;

    public function __construct(
        ConcertId $concertId,
        EmailAddress $emailAddress,
        int $numberOfSeats
    ) {
        $this->concertId = $concertId;
        $this->emailAddress = $emailAddress;
        $this->numberOfSeats = $numberOfSeats;
    }

    public function concertId(): ConcertId
    {
        return $this->concertId;
    }

    public function emailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }

    public function numberOfSeats(): int
    {
        return $this->numberOfSeats;
    }
}