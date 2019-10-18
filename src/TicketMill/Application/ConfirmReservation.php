<?php
declare(strict_types=1);

namespace TicketMill\Application;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Domain\Model\Concert\ReservationWasAccepted;
use TicketMill\Domain\Model\Reservation\ReservationRepository;

final class ConfirmReservation
{
    /**
     * @var ReservationRepository
     */
    private $reservationRepository;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(
        ReservationRepository $reservationRepository,
        EventDispatcher $eventDispatcher
    ) {
        $this->reservationRepository = $reservationRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function whenReservationWasAccepted(ReservationWasAccepted $event): void
    {
        $reservation = $this->reservationRepository->getById($event->reservationId());

        $reservation->confirm();

        $this->reservationRepository->save($reservation);

        $this->eventDispatcher->dispatchAll($reservation->releaseEvents());
    }
}
