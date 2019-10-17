<?php
declare(strict_types=1);

namespace TicketMill\Application;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Domain\Model\Concert\ConcertId;
use TicketMill\Domain\Model\Concert\ConcertRepository;
use TicketMill\Domain\Model\Concert\ReservationId;

final class CancelReservation
{
    /**
     * @var ConcertRepository
     */
    private $concertRepository;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(
        ConcertRepository $concertRepository,
        EventDispatcher $eventDispatcher
    ) {
        $this->concertRepository = $concertRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function cancelReservation(string $concertId, int $reservationId): void
    {
        $concert = $this->concertRepository->getById(
            ConcertId::fromString($concertId)
        );

        $concert->cancelReservation(ReservationId::fromInt($reservationId));

        $this->concertRepository->save($concert);

        $this->eventDispatcher->dispatchAll($concert->releaseEvents());
    }
}
