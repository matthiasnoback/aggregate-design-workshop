<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Reservation;

use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Common\EventRecording;
use TicketMill\Domain\Model\Concert\ConcertId;
use TicketMill\Domain\Model\Concert\ReservationId;
use TicketMill\Domain\Model\Concert\ReservationWasCancelled;
use TicketMill\Domain\Model\Concert\ReservationWasMade;

final class Reservation
{
    use EventRecording;

    private ReservationId $reservationId;
    private ConcertId $concertId;
    private EmailAddress $emailAddress;
    private int $numberOfSeats;
    private bool $isCancelled = false;

    private function __construct(
        ReservationId $reservationId,
        ConcertId $concertId,
        EmailAddress $emailAddress,
        int $numberOfSeats
    ) {
        $this->reservationId = $reservationId;
        $this->concertId = $concertId;
        $this->emailAddress = $emailAddress;
        $this->numberOfSeats = $numberOfSeats;
    }

    public static function make(
        ReservationId $reservationId,
        ConcertId $concertId,
        EmailAddress $emailAddress,
        int $numberOfSeats
    ): Reservation {
        $instance = new self(
            $reservationId,
            $concertId,
            $emailAddress,
            $numberOfSeats
        );

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
        if ($this->isCancelled) {
            return;
        }

        $this->isCancelled = true;
        $this->recordThat(
            new ReservationWasCancelled(
                $this->reservationId,
                $this->concertId,
                $this->numberOfSeats
            )
        );
    }
}
