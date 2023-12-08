<?php
declare(strict_types=1);

namespace TicketMill\Application;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Domain\Model\Concert\ConcertId;
use TicketMill\Domain\Model\Concert\ConcertRepository;
use TicketMill\Domain\Model\Concert\ReservationId;
use TicketMill\Domain\Model\Reservation\ReservationRepository;

final class CancelReservation
{
    private EventDispatcher $eventDispatcher;
    private ReservationRepository $reservationRepository;

    public function __construct(
        ReservationRepository $reservationRepository,
        EventDispatcher $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->reservationRepository = $reservationRepository;
    }

    public function cancelReservation(string $reservationId): void
    {
        $reservation = $this->reservationRepository->getById(ReservationId::fromString($reservationId));

        $reservation->cancel();

        $this->reservationRepository->save($reservation);

        $this->eventDispatcher->dispatchAll($reservation->releaseEvents());
    }
}
