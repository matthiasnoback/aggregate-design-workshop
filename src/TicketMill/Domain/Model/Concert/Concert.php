<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use Assert\Assertion;
use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Common\EventRecording;

final class Concert
{
    use EventRecording;

    private ConcertId $concertId;
    private ScheduledDate $date;
    private bool $isCancelled = false;

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

    public function makeReservation(ReservationId $reservationId, EmailAddress $emailAddress,
        int $numberOfSeats
    ): void {
    }

    public function cancelReservation(ReservationId $reservationId): void
    {
    }

    public function numberOfSeatsAvailable(): int
    {
        return 0;
    }
}
