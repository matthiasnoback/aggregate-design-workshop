<?php

namespace TicketMill\Domain\Model\Reservation;

use TicketMill\Domain\Model\Concert\CouldNotFindReservation;
use TicketMill\Domain\Model\Concert\ReservationId;

interface ReservationRepository
{
    /**
     * @throws CouldNotFindReservation
     */
    public function getById(ReservationId $reservationId): Reservation;

    public function nextIdentity(): ReservationId;

    public function save(Reservation $reservation): void;
}
