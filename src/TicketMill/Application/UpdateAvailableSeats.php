<?php
declare(strict_types=1);

namespace TicketMill\Application;

use TicketMill\Domain\Model\Concert\ConcertWasPlanned;
use TicketMill\Domain\Model\Concert\ReservationWasAccepted;
use TicketMill\Domain\Model\Reservation\ReservationWasCancelled;

final class UpdateAvailableSeats
{
    /**
     * @var array&AvailableSeats[]
     */
    private $availableSeats = [];

    public function whenConcertWasPlanned(ConcertWasPlanned $event): void
    {
        $availableSeats = new AvailableSeats(
            $event->concertId(),
            $event->numberOfSeats()
        );
        $this->availableSeats[$event->concertId()->asString()] = $availableSeats;

        echo $availableSeats->numberOfAvailableSeats() . "\n";
    }

    public function whenReservationWasAccepted(ReservationWasAccepted $event): void
    {
        $availableSeats = $this->availableSeats[$event->concertId()->asString()];

        $updatedAvailableSeats = $availableSeats->withNumberOfSeatsReserved($event->numberOfSeats());

        $this->availableSeats[$event->concertId()->asString()] = $updatedAvailableSeats;

        echo $updatedAvailableSeats->numberOfAvailableSeats() . "\n";
    }

    public function whenReservationWasCancelled(ReservationWasCancelled $event): void
    {
        $availableSeats = $this->availableSeats[$event->concertId()->asString()];

        $updatedAvailableSeats = $availableSeats->withNumberOfSeatsCancelled($event->numberOfSeats());

        $this->availableSeats[$event->concertId()->asString()] = $updatedAvailableSeats;

        echo $updatedAvailableSeats->numberOfAvailableSeats() . "\n";
    }
}
