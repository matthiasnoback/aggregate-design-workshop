<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use TicketMill\Domain\Model\Reservation\ReservationId;

final class ReservationWasAccepted
{
    /**
     * @var ReservationId
     */
    private $reservationId;

    public function __construct(ReservationId $reservationId)
    {
        $this->reservationId = $reservationId;
    }

    public function reservationId(): ReservationId
    {
        return $this->reservationId;
    }
}
