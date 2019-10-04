<?php

namespace TicketMill\Domain\Model\Concert;

interface ConcertRepository
{
    /**
     * @throws CouldNotFindConcert
     */
    public function getById(ConcertId $concertId): Concert;

    public function nextIdentity(): ConcertId;

    public function save(Concert $concert): void;
}
