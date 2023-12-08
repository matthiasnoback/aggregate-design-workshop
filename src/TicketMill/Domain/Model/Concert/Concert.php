<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use Assert\Assertion;
use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Common\EventRecording;
use TicketMill\Domain\Model\Reservation\ReservationId;

final class Concert
{
    use EventRecording;

    private ConcertId $concertId;

    private ScheduledDate $date;

    private bool $wasCancelled = false;

    private int $numberOfSeatsInTheBuilding;
    private int $numberOfSeatsReserved = 0;

    private function __construct()
    {
    }

    public static function plan(
        ConcertId $concertId,
        string $name,
        ScheduledDate $date,
        int $numberOfSeats
    ): self {
        Assertion::notEmpty($name, 'The name cannot be empty');
        Assertion::greaterThan($numberOfSeats, 0, 'You have to plan more than 0 seats');

        $instance = new self();

        $instance->concertId = $concertId;
        $instance->date = $date;
        $instance->numberOfSeatsInTheBuilding = $numberOfSeats;

        return $instance;
    }

    public function concertId(): ConcertId
    {
        return $this->concertId;
    }

    public function reschedule(ScheduledDate $newDate): void
    {
        if ($newDate->equals($this->date)) {
            return;
        }

        if ($this->wasCancelled) {
            throw CouldNotRescheduleConcert::becauseItWasAlreadyCancelled();
        }

        $this->date = $newDate;
        $this->recordThat(
            new ConcertWasRescheduled($this->concertId)
        );
    }

    public function cancel(): void
    {
        if ($this->wasCancelled) {
            return;
        }

        $this->wasCancelled = true;
        $this->recordThat(new ConcertWasCancelled($this->concertId));
    }

    private function numberOfSeatsAvailable(): int
    {
        return $this->numberOfSeatsInTheBuilding - $this->numberOfSeatsReserved();
    }

    public function areSeatsAvailable(int $numberOfSeats): bool
    {
        return $this->numberOfSeatsAvailable() >= $numberOfSeats;
    }

    private function numberOfSeatsReserved(): int
    {
        return $this->numberOfSeatsReserved;
    }

    public function reservationWasMade(int $numberOfSeats): void
    {
        $this->numberOfSeatsReserved += $numberOfSeats;
    }

    public function reservationWasCancelled(int $numberOfSeats): void
    {
        $this->numberOfSeatsReserved -= $numberOfSeats;
    }

    public function processReservation(ReservationId $reservationId, int $numberOfSeats, EmailAddress $emailAddress): void
    {
        if ($this->areSeatsAvailable($numberOfSeats)) {
            $this->numberOfSeatsReserved += $numberOfSeats;
            $this->recordThat(new ReservationWasAccepted($reservationId));
        } else {
            //
            $this->recordThat(new ReservationWasRejected($reservationId, $emailAddress));
        }
    }
}
