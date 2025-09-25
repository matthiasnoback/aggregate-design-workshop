<?php

namespace TicketMill\Application;

use TicketMill\Domain\Model\Concert\ConcertRepository;
use TicketMill\Domain\Model\Reservation\ReservationWasCancelled;
use TicketMill\Domain\Model\Reservation\ReservationWasMade;

final readonly class UpdateConcertSeatsAvailable
{
    public function __construct(
        private ConcertRepository $concertRepository
    ) {

    }

    public function whenReservationWasMade(ReservationWasMade $reservationWasMade): void
    {
        $concert = $this->concertRepository->getById($reservationWasMade->concertId());

        $concert->decreaseSeatsAvailable($reservationWasMade->numberOfSeats());

        $this->concertRepository->save($concert);
    }

    public function whenReservationWasCancelled(ReservationWasCancelled $reservationWasCancelled): void
    {
        $concert = $this->concertRepository->getById($reservationWasCancelled->concertId());

        $concert->increaseSeatsAvailable($reservationWasCancelled->numberOfSeats());

        $this->concertRepository->save($concert);
    }
}
