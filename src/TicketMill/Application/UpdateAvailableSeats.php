<?php
declare(strict_types=1);

namespace TicketMill\Application;

use TicketMill\Domain\Model\Concert\ConcertRepository;
use TicketMill\Domain\Model\Reservation\ReservationWasCancelled;
use TicketMill\Domain\Model\Reservation\ReservationWasMade;

final class UpdateAvailableSeats
{
    /**
     * @var ConcertRepository
     */
    private $concertRepository;

    public function __construct(ConcertRepository $concertRepository)
    {
        $this->concertRepository = $concertRepository;
    }

    public function whenReservationWasMade(ReservationWasMade $event): void
    {
        $concert = $this->concertRepository->getById($event->concertId());

        $concert->decreaseNumberOfAvailableSeats($event->numberOfSeats());

        $this->concertRepository->save($concert);
    }

    public function whenReservationWasCancelled(ReservationWasCancelled $event): void
    {
        $concert = $this->concertRepository->getById($event->concertId());

        $concert->increaseNumberOfAvailableSeats($event->numberOfSeats());

        $this->concertRepository->save($concert);
    }
}
