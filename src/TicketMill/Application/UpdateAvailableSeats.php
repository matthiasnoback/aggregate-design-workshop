<?php
declare(strict_types=1);

namespace TicketMill\Application;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Domain\Model\Concert\ConcertRepository;
use TicketMill\Domain\Model\Reservation\ReservationWasCancelled;
use TicketMill\Domain\Model\Reservation\ReservationWasMade;

final class UpdateAvailableSeats
{
    private ConcertRepository $concertRepository;
    private EventDispatcher $eventDispatcher;

    public function __construct(ConcertRepository $concertRepository, EventDispatcher $eventDispatcher)
    {
        $this->concertRepository = $concertRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function whenReservationWasMade(ReservationWasMade $event): void
    {
        $concert = $this->concertRepository->getById($event->concertId());

        $concert->processReservation($event->numberOfSeats());

        $this->concertRepository->save($concert);

        $this->eventDispatcher->dispatchAll($concert->releaseEvents());
    }

    public function whenReservationWasCancelled(ReservationWasCancelled $event): void
    {
        $concert = $this->concertRepository->getById($event->concertId());

        $concert->processReservationCancellation($event->numberOfSeats());

        $this->concertRepository->save($concert);
    }
}
