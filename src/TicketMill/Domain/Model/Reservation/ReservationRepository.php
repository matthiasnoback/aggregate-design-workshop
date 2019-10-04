<?php

namespace TicketMill\Domain\Model\Reservation;

interface ReservationRepository
{
    /**
     * @throws CouldNotFindReservation
     */
    public function getById(ReservationId $reservationId): Reservation;

    public function nextIdentity(): ReservationId;

    public function save(Reservation $reservation): void;
}
