<?php
declare(strict_types=1);

namespace TicketMill\Application;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Domain\Model\Concert\ReservationWasAccepted;
use TicketMill\Domain\Model\Reservation\ReservationRepository;

final class ConfirmReservation
{
    private ReservationRepository $repository;
    private EventDispatcher $eventDispatcher;

    public function __construct(ReservationRepository $repository, EventDispatcher $eventDispatcher)
    {
        $this->repository = $repository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function whenReservationWasAccepted(ReservationWasAccepted $event): void
    {
        $reservation = $this->repository->getById(
            $event->reservationId()
        );

        $reservation->confirm();

        $this->repository->save($reservation);

        $this->eventDispatcher->dispatchAll($reservation->releaseEvents());
    }
}
