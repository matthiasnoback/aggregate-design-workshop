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
    private $wasCancelled = false;

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

    public function cancel()
    {
        if ($this->wasCancelled) {
            return;
        }

        $this->wasCancelled = true;
        $this->recordThat(new ReservationWasCancelled($this->concertId, $this->numberOfSeats));
    }
}
