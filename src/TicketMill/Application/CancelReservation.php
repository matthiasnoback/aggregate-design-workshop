<?php
declare(strict_types=1);

namespace TicketMill\Application;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Domain\Model\Concert\ConcertId;
use TicketMill\Domain\Model\Concert\ConcertRepository;
use TicketMill\Domain\Model\Reservation\ReservationId;
use TicketMill\Domain\Model\Reservation\ReservationRepository;

final class CancelReservation
{
    /**
     * @var ConcertRepository
     */
    private $concertRepository;

    /**
     * @var ReservationRepository
     */
    private $reservationRepository;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(
        ConcertRepository $concertRepository,
        ReservationRepository $reservationRepository,
        EventDispatcher $eventDispatcher
    ) {
        $this->concertRepository = $concertRepository;
        $this->reservationRepository = $reservationRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function cancelReservation(string $concertId, string $reservationId): void
    {
        $concert = $this->concertRepository->getById(
            ConcertId::fromString($concertId)
        );

        $reservation = $this->reservationRepository->getById(ReservationId::fromString($reservationId));

        $reservation->cancel();

        $this->concertRepository->save($concert);

        $this->eventDispatcher->dispatchAll($concert->releaseEvents());
    }
}
