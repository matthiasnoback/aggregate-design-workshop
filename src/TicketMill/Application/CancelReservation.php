<?php
declare(strict_types=1);

namespace TicketMill\Application;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Domain\Model\Concert\ConcertId;
use TicketMill\Domain\Model\Concert\ConcertRepository;
use TicketMill\Domain\Model\Concert\ReservationId;

final class CancelReservation
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

    public function cancelReservation(string $concertId, string $reservationId): void
    {
        $concert = $this->concertRepository->getById(
            ConcertId::fromString($concertId)
        );

        $concert->cancelReservation(ReservationId::fromString($reservationId));

        $this->concertRepository->save($concert);

        $this->eventDispatcher->dispatchAll($concert->releaseEvents());
    }
}
