<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use Assert\Assertion;
use RuntimeException;
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

    /**
     * @var int
     */
    private $numberOfSeats;

    /**
     * @var array<Reservation> & Reservation[]
     */
    private $reservations = [];

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
        $this->numberOfSeats = $numberOfSeats;
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

        $this->date = $newDate;
        $this->events[] = new ConcertWasRescheduled();
    }

    public function cancel(): void
    {
        if ($this->wasCancelled) {
            return;
        }

        $this->recordThat(new ConcertWasCancelled());

        $this->wasCancelled = true;
    }

    public function makeReservation(ReservationId $reservationId, EmailAddress $emailAddress,
        int $numberOfSeats
    ): void {
        if ($this->numberOfSeatsAvailable() < $numberOfSeats) {
            throw CouldNotReserveSeats::becauseNotEnoughSeatsWereAvailable($numberOfSeats);
        }
        $this->reservations[$reservationId->asString()] = new Reservation(
            $reservationId,
            $emailAddress,
            $numberOfSeats
        );

        $this->recordThat(new ReservationWasMade($reservationId, $this->concertId, $emailAddress, $numberOfSeats));
    }

    public function cancelReservation(ReservationId $reservationId): void
    {
        if (!isset($this->reservations[$reservationId->asString()])) {
            throw new RuntimeException('Reservation not found: ' . $reservationId->asString());
        }

        $reservation = $this->reservations[$reservationId->asString()];
        unset($this->reservations[$reservationId->asString()]);

        $this->recordThat(new ReservationWasCancelled($reservationId, $this->concertId, $reservation->numberOfSeats()));
    }

    public function numberOfSeatsAvailable(): int
    {
        $available = $this->numberOfSeats;

        foreach ($this->reservations as $reservation) {
            $available -= $reservation->numberOfSeats();
        }

        return $available;
    }
}
