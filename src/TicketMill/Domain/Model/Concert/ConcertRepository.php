<?php

namespace TicketMill\Domain\Model\Concert;

use TicketMill\Domain\Model\Reservation\ReservationId;

interface ConcertRepository
{
    /**
     * @throws CouldNotFindConcert
     */
    public function getById(ConcertId $concertId): Concert;

    public function nextIdentity(): ConcertId;

    public function nextReservationId(): ReservationId;

    public function save(Concert $concert): void;
}
