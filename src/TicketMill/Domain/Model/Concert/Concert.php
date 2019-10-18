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
     * @var array&Reservation[]
     */
    private $reservations = [];

    /**
     * @var ReservationId|null
     */
    private $lastUsedReservationId;

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

    public function makeReservation(EmailAddress $emailAddress,
        int $numberOfSeats
    ): ReservationId {
        if ($this->numberOfSeatsAvailable() < $numberOfSeats) {
            throw CouldNotReserveSeats::becauseNotEnoughSeatsWereAvailable($numberOfSeats);
        }

        $nextReservationId = $this->lastUsedReservationId instanceof ReservationId
            ? $this->lastUsedReservationId->next()
            : ReservationId::fromInt(1);
        $this->lastUsedReservationId = $nextReservationId;

        $this->reservations[$nextReservationId->asInt()] = new Reservation(
            $nextReservationId,
            $emailAddress,
            $numberOfSeats
        );

        $this->recordThat(new ReservationWasMade($this->concertId, $emailAddress, $numberOfSeats));

        return $nextReservationId;
    }

    public function cancelReservation(ReservationId $reservationId): void
    {
        if (!isset($this->reservations[$reservationId->asInt()])) {
            throw new RuntimeException('Reservation not found: ' . $reservationId->asInt());
        }

        unset($this->reservations[$reservationId->asInt()]);
    }

    public function numberOfSeatsAvailable(): int
    {
        return $this->numberOfSeats - $this->numberOfSeatsReserved();
    }

    private function numberOfSeatsReserved(): int
    {
        $numberOfSeatsReserved = 0;

        foreach ($this->reservations as $reservation) {
            $numberOfSeatsReserved += $reservation->numberOfSeats();
        }

        return $numberOfSeatsReserved;
    }
}
