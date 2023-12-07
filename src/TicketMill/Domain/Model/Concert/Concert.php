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

        $this->date = $newDate;
        $this->recordThat(
            new ConcertWasRescheduled($this->concertId)
        );
    }

    public function cancel(): void
    {
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
