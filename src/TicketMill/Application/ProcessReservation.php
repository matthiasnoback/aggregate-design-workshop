<?php
declare(strict_types=1);

namespace TicketMill\Application;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Domain\Model\Concert\ConcertRepository;
use TicketMill\Domain\Model\Concert\ReservationWasAccepted;
use TicketMill\Domain\Model\Reservation\ReservationRepository;
use TicketMill\Domain\Model\Reservation\ReservationWasCancelled;
use TicketMill\Domain\Model\Reservation\ReservationWasMade;

final class ProcessReservation
{
    private ConcertRepository $concertRepository;
    private EventDispatcher $eventDispatcher;
    private ReservationRepository $reservationRepository;

    public function __construct(
        ConcertRepository $concertRepository,
        ReservationRepository $reservationRepository,
        EventDispatcher $eventDispatcher
    ) {
        $this->concertRepository = $concertRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->reservationRepository = $reservationRepository;
    }

    public function whenReservationWasMade(ReservationWasMade $event): void
    {
        $concert = $this->concertRepository->getById($event->concertId());

        $concert->processReservation($event);

        $this->concertRepository->save($concert);

        $this->eventDispatcher->dispatchAll($concert->releaseEvents());
    }

    public function whenReservationWasCancelled(ReservationWasCancelled $event): void
    {
        $concert = $this->concertRepository->getById($event->concertId());

        $concert->processReservationCancellation($event);

        $this->concertRepository->save($concert);

        $this->eventDispatcher->dispatchAll($concert->releaseEvents());
    }

    public function whenReservationWasAccepted(ReservationWasAccepted $event): void
    {
        $reservation = $this->reservationRepository->getById($event->reservationId());

        $reservation->confirm();

        $this->reservationRepository->save($reservation);

        $this->eventDispatcher->dispatchAll($reservation->releaseEvents());
    }
}
