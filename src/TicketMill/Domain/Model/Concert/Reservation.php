<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use TicketMill\Domain\Model\Common\EmailAddress;

final class Reservation
{
    /**
     * @var EmailAddress
     */
    private $emailAddress;

    /**
     * @var int
     */
    private $numberOfSeats;

    public function __construct(
        EmailAddress $emailAddress,
        int $numberOfSeats
    ) {
        $this->emailAddress = $emailAddress;
        $this->numberOfSeats = $numberOfSeats;
    }
}
