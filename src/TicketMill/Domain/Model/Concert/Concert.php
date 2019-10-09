<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use Assert\Assertion;
use TicketMill\Domain\Model\Common\EventRecording;

final class Concert
{
    use EventRecording;

    /**
     * @var ConcertId
     */
    private $concertId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ScheduledDate
     */
    private $date;

    /**
     * @var bool
     */
    private $isCancelled = false;

    /**
     * @var int
     */
    private $numberOfSeats;

    /**
     * @var int
     */
    private $numberOfSeatsAvailable;

    private function __construct(
        ConcertId $concertId,
        string $name,
        ScheduledDate $date,
        int $numberOfSeats
    ) {
        $this->concertId = $concertId;

        Assertion::notEq($name, '', 'Name should not be empty');
        $this->name = $name;

        $this->date = $date;

        Assertion::greaterThan($numberOfSeats, 0, 'Number of seats should be greater than 0');
        $this->numberOfSeats = $numberOfSeats;

        $this->numberOfSeatsAvailable = $numberOfSeats;
    }

    public static function plan(
        ConcertId $concertId,
        string $name,
        ScheduledDate $date,
        int $numberOfSeats
    ): Concert {
        return new self(
            $concertId,
            $name,
            $date,
            $numberOfSeats
        );
    }

    public function concertId(): ConcertId
    {
        return $this->concertId;
    }

    public function reschedule(ScheduledDate $newDate): void
    {
        if ($this->isCancelled) {
            throw CouldNotRescheduleConcert::becauseItWasAlreadyCancelled();
        }

        if ($this->date->equals($newDate)) {
            return;
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
        // TODO make this return the correct value, taking into account the reservations
        return $this->numberOfSeatsAvailable;
    }

    public function processReservation(int $numberOfSeatsReserved): void
    {
        $this->numberOfSeatsAvailable -= $numberOfSeatsReserved;
    }
}
