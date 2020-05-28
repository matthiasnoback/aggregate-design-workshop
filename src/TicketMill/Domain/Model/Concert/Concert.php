<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use Assert\Assertion;
use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Common\EventRecording;

final class Concert
{
    use EventRecording;

    /**
     * @var ConcertId
     */
    private $concertId;

    /**
     * @var ScheduledDate
     */
    private $date;

    /**
     * @var bool
     */
    private $wasCancelled = false;

    private function __construct(
        ConcertId $concertId,
        string $name,
        ScheduledDate $date,
        int $numberOfSeats
    ) {
        Assertion::notEq($name, '', 'The name of a concert should not be empty');
        Assertion::greaterThan($numberOfSeats, 0, 'Number of seats should be greater than 0');

        $this->concertId = $concertId;
        $this->date = $date;
    }

    public static function plan(
        ConcertId $concertId,
        string $name,
        ScheduledDate $date,
        int $numberOfSeats
    ): Concert
    {
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
        if ($this->date->equals($newDate)) {
            return;
        }

        if ($this->wasCancelled) {
            throw CouldNotRescheduleConcert::becauseItWasAlreadyCancelled();
        }

        $this->recordThat(new ConcertWasRescheduled());
        $this->date = $newDate;
    }

    public function cancel(): void
    {
        if ($this->wasCancelled) {
            return;
        }

        $this->recordThat(new ConcertWasCancelled($this->concertId));

        $this->wasCancelled = true;
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
