<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Reservation;

final class Reservation
{
    /**
     * @var ReservationId
     */
    private $reservationId;

    private function __construct()
    {
    }

    public static function make(ReservationId $reservationId): Reservation
    {
        $reservation = new self();

        $reservation->reservationId = $reservationId;

        return $reservation;
    }

    public function reservationId(): ReservationId
    {
        return $this->reservationId;
    }
}
