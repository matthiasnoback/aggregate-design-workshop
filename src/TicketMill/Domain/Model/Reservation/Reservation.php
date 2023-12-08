<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Reservation;

use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Common\EventRecording;
use TicketMill\Domain\Model\Concert\ConcertId;

final class Reservation
{
    use EventRecording;

    private ReservationId $reservationId;
    private ConcertId $concertId;
    private EmailAddress $emailAddress;
    private int $numberOfSeats;

    private bool $wasCancelled = false;

    private function __construct(
    ) {
    }

    public static function make(
        ReservationId $reservationId,
        ConcertId $concertId,
        EmailAddress $emailAddress,
        int $numberOfSeats
    ): Reservation {
        $instance = new self();

        $instance->reservationId = $reservationId;
        $instance->concertId = $concertId;
        $instance->emailAddress = $emailAddress;
        $instance->numberOfSeats = $numberOfSeats;

        $instance->recordThat(
            new ReservationWasMade(
                $reservationId,
                $concertId,
                $emailAddress,
                $numberOfSeats
            )
        );

        return $instance;
    }

    public function reservationId(): ReservationId
    {
        return $this->reservationId;
    }

    public function cancel(): void
    {
        if ($this->wasCancelled) {
            return;
        }

        $this->wasCancelled = true;
        $this->recordThat(
            new ReservationWasCancelled(
                $this->reservationId,
                $this->concertId,
                $this->numberOfSeats
            )
        );
    }
}
