<?php

namespace TicketMill\Application;

use TicketMill\Domain\Model\Concert\ConcertRepository;
use TicketMill\Domain\Model\Reservation\ReservationWasCancelled;
use TicketMill\Domain\Model\Reservation\ReservationWasMade;

class UpdateSeatsAvailable
{
    private ConcertRepository $concertRepository;

    public function __construct(ConcertRepository $concertRepository)
    {
        $this->concertRepository = $concertRepository;
    }
    public function whenReservationWasMade(ReservationWasMade $event): void
    {
        $concert = $this->concertRepository->getById($event->concertId());

        $concert->reservationWasMade($event->numberOfSeats());

        $this->concertRepository->save($concert);
    }

    public function whenReservationWasCancelled(ReservationWasCancelled $event): void
    {
        $concert = $this->concertRepository->getById($event->concertId());

        $concert->reservationWasCancelled($event->numberOfSeats());

        $this->concertRepository->save($concert);
    }
}
