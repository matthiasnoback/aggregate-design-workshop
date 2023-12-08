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

    private bool $wasCancelled = false;

    private int $numberOfSeats;

    /**
     * @var array<string,Reservation>
     */
    private array $reservations = [];

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
        $instance->numberOfSeats = $numberOfSeats;

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

        if ($this->wasCancelled) {
            throw CouldNotRescheduleConcert::becauseItWasAlreadyCancelled();
        }

        $this->date = $newDate;
        $this->recordThat(
            new ConcertWasRescheduled($this->concertId)
        );
    }

    public function cancel(): void
    {
        if ($this->wasCancelled) {
            return;
        }

        $this->wasCancelled = true;
        $this->recordThat(new ConcertWasCancelled($this->concertId));
    }

    public function makeReservation(ReservationId $reservationId, EmailAddress $emailAddress,
        int $numberOfSeats
    ): void {
        if (! $this->areSeatsAvailable($numberOfSeats)) {
            throw CouldNotReserveSeats::becauseNotEnoughSeatsWereAvailable($numberOfSeats);
        }
        $this->reservations[$reservationId->asString()] = new Reservation(
            $reservationId,
            $emailAddress,
            $numberOfSeats
        );

        $this->recordThat(
            new ReservationWasMade(
                $reservationId,
                $this->concertId,
                $emailAddress,
                $numberOfSeats
            )
        );
    }

    public function cancelReservation(ReservationId $reservationId): void
    {
        if (! array_key_exists($reservationId->asString(), $this->reservations)) {
            throw new \RuntimeException('Could not find reservation');
        }
        $reservation = $this->reservations[$reservationId->asString()];
        unset($this->reservations[$reservationId->asString()]);
        $this->recordThat(new ReservationWasCancelled($reservationId, $this->concertId, $reservation->numberOfSeats()));
    }

    public function numberOfSeatsAvailable(): int
    {
        return $this->numberOfSeats - array_reduce(
            $this->reservations,
            function (int $carry, Reservation $reservation) {
                return $carry + $reservation->numberOfSeats();
            },
            0
        );
    }

    private function areSeatsAvailable(int $numberOfSeats): bool
    {
        return $this->numberOfSeatsAvailable() >= $numberOfSeats;
    }
}
