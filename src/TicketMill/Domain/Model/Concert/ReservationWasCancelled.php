<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

final class ReservationWasCancelled
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
