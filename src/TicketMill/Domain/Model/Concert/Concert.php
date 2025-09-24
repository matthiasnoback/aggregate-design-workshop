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

    private ScheduledDate $scheduledDate;

    private bool $wasCancelled = false;

    private function __construct()
    {
    }

    public static function plan(
        ConcertId $concertId,
        string $name,
        ScheduledDate $date,
        int $numberOfSeats
    ): self {
        $instance = new self();

        Assertion::notBlank($name, 'The name should not be empty');
        Assertion::greaterThan($numberOfSeats, 0, 'The number of seats should be greater than 0');

        $instance->concertId = $concertId;
        $instance->scheduledDate = $date;

        return $instance;
    }

    public function concertId(): ConcertId
    {
        return $this->concertId;
    }

    public function reschedule(ScheduledDate $newDate): void
    {
        if ($this->wasCancelled) {
            throw CouldNotRescheduleConcert::becauseItWasAlreadyCancelled();
        }

        if ($newDate->equals($this->scheduledDate)) {
            return;
        }

        $this->scheduledDate = $newDate;
        $this->recordThat(new ConcertWasRescheduled());
    }

    public function cancel(): void
    {
        if ($this->wasCancelled) {
            return;
        }

        $this->wasCancelled = true;
        $this->recordThat(new ConcertWasCancelled());
    }

    public function makeReservation(
        ReservationId $reservationId,
        EmailAddress $emailAddress,
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
