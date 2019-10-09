<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Reservation;

use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Common\EventRecording;
use TicketMill\Domain\Model\Concert\ConcertId;

final class Reservation
{
    use EventRecording;

    /**
     * @var ReservationId
     */
    private $reservationId;

    /**
     * @var ConcertId
     */
    private $concertId;

    /**
     * @var EmailAddress
     */
    private $emailAddress;

    /**
     * @var int
     */
    private $numberOfSeats;

    /**
     * @var bool
     */
    private $isConfirmed = false;

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

        $this->recordThat(
            new ReservationWasMade(
                $reservationId,
                $concertId,
                $emailAddress,
                $numberOfSeats
            )
        );
    }

    public static function make(
        ReservationId $reservationId,
        ConcertId $concertId,
        EmailAddress $emailAddress,
        int $numberOfSeats
    ): Reservation {
        return new self(
            $reservationId,
            $concertId,
            $emailAddress,
            $numberOfSeats
        );
    }

    public function reservationId(): ReservationId
    {
        return $this->reservationId;
    }

    public function confirm()
    {
        if ($this->isConfirmed) {
            return;
        }

        $this->isConfirmed = true;
        $this->recordThat(
            new ReservationWasConfirmed(
                $this->reservationId,
                $this->concertId,
                $this->emailAddress,
                $this->numberOfSeats
            )
        );
    }

    public function isConfirmed(): bool
    {
        return $this->isConfirmed;
    }
}
