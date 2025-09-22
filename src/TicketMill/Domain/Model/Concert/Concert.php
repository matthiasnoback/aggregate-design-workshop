<?php

declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Common\EventRecording;

final class Concert
{
    use EventRecording;

    private ConcertId $concertId;

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

        $instance->concertId = $concertId;

        return $instance;
    }

    public function concertId(): ConcertId
    {
        return $this->concertId;
    }

    public function reschedule(ScheduledDate $newDate): void
    {
    }

    public function cancel(): void
    {
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
