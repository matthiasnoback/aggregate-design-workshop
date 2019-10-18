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
        Assertion::notEq($name, '', 'Name should not be empty');
        Assertion::greaterThan($numberOfSeats, 0, 'Number of seats should be greater than 0');

        $this->concertId = $concertId;
        $this->date = $date;
        $this->name = $name;
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
        return $this->numberOfSeatsAvailable;
    }

    public function decreaseNumberOfSeatsAvailable(int $numberOfSeats): void
    {
        $this->numberOfSeatsAvailable -= $numberOfSeats;
    }

    public function increaseNumberOfSeatsAvailable(int $numberOfSeats): void
    {
        $this->numberOfSeatsAvailable += $numberOfSeats;
    }
}
