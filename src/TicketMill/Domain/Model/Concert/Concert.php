<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use Assert\Assertion;
use TicketMill\Domain\Model\Common\EventRecording;
use TicketMill\Domain\Model\Reservation\ReservationId;

final class Concert
{
    use EventRecording;

    private ConcertId $concertId;
    private ScheduledDate $date;
    private bool $isCancelled = false;
    private int $numberOfSeats;
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
        Assertion::notEmpty(
            $name,
            'The name of a concert should not be empty');
        Assertion::greaterThan(
            $numberOfSeats,
            0,
            'The number of seats for a concert should be greater than 0'
        );

        $instance = new self();

        $instance->concertId = $concertId;
        $instance->date = $date;
        $instance->numberOfSeats = $numberOfSeats;

        return $instance;
    }

    public function concertId(): ConcertId
    {
        return $this->concertId;
    }

    public function reschedule(ScheduledDate $newDate): void
    {
        if ($this->date->equals($newDate)) {
            return;
        }

        if ($this->isCancelled) {
            throw CouldNotRescheduleConcert::becauseItWasAlreadyCancelled();
        }

        $this->date = $newDate;
        $this->recordThat(new ConcertWasRescheduled());
    }

    public function cancel(): void
    {
        if ($this->isCancelled) {
            return;
        }

        $this->isCancelled = true;
        $this->recordThat(new ConcertWasCancelled());
    }

    public function numberOfSeatsAvailable(): int
    {
        return $this->numberOfSeats - $this->numberOfSeatsReserved;
    }

    public function processReservation(
        int $numberOfSeats,
        ReservationId $reservationId
    ): void
    {
        if ($numberOfSeats > $this->numberOfSeatsAvailable()) {
            $this->recordThat(new ReservationWasRejected());
            return;
        }

        $this->numberOfSeatsReserved += $numberOfSeats;
        $this->recordThat(new ReservationWasAccepted($reservationId));
    }

    public function processReservationCancellation(int $numberOfSeats): void
    {
        $this->numberOfSeatsReserved -= $numberOfSeats;
    }
}
