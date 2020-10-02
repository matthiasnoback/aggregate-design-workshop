<?php
declare(strict_types=1);

namespace TicketMill\Application;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Concert\ConcertId;
use TicketMill\Domain\Model\Concert\ConcertRepository;
use TicketMill\Domain\Model\Concert\ReservationId;

final class MakeReservation
{
    private ConcertRepository $concertRepository;
    private EventDispatcher $eventDispatcher;

    public function __construct(
        ConcertRepository $concertRepository,
        EventDispatcher $eventDispatcher
    ) {
        $this->concertRepository = $concertRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function makeReservation(string $concertId, string $emailAddress, int $numberOfSeats): ReservationId
    {
        $concert = $this->concertRepository->getById(ConcertId::fromString($concertId));

        $reservationId = $this->concertRepository->nextReservationId();

        $concert->makeReservation(
            $reservationId,
            EmailAddress::fromString($emailAddress),
            $numberOfSeats
        );

        $this->concertRepository->save($concert);

        $this->eventDispatcher->dispatchAll($concert->releaseEvents());

        return $reservationId;
    }
}
