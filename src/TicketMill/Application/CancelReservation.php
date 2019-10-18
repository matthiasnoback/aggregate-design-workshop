<?php
declare(strict_types=1);

namespace TicketMill\Application;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Domain\Model\Reservation\ReservationId;
use TicketMill\Domain\Model\Reservation\ReservationRepository;

final class CancelReservation
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

    public function cancelReservation(string $reservationId): void
    {
        $reservation = $this->reservationRepository->getById(ReservationId::fromString($reservationId));

        $reservation->cancel();

        $this->reservationRepository->save($reservation);

        $this->eventDispatcher->dispatchAll($reservation->releaseEvents());
    }
}
