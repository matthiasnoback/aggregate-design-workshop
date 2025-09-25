<?php

declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use Assert\Assertion;
use TicketMill\Domain\Model\Common\EventRecording;

final class Concert
{
    use EventRecording;

    private ConcertId $concertId;

    private ScheduledDate $scheduledDate;

    private bool $wasCancelled = false;

    private int $numberOfSeats;

    private int $numberOfSeatsAvailable;

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
        $instance->numberOfSeats = $numberOfSeats;
        $instance->numberOfSeatsAvailable = $numberOfSeats;

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

    public function canBeBooked(int $numberOfSeats): bool
    {
        return $this->numberOfSeatsAvailable >= $numberOfSeats;
    }

    public function decreaseSeatsAvailable(int $numberOfSeats): void
    {
        $this->numberOfSeatsAvailable -= $numberOfSeats;
    }

    public function increaseSeatsAvailable(int $numberOfSeats): void
    {
        $this->numberOfSeatsAvailable += $numberOfSeats;
    }
}
