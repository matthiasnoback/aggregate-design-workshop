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

    private ConcertId $concertId;
    private ScheduledDate $date;
    private bool $isCancelled = false;

    /**
     * @var Reservation[] $reservations
     */
    private array $reservations = [];
    private int $numberOfSeats;

    private function __construct()
    {
    }

    public static function plan(
        ConcertId $concertId,
        string $name,
        ScheduledDate $date,
        int $numberOfSeats
    ): self {
        Assertion::notEmpty(
            $name,
            'The name of a concert should not be empty');
        Assertion::greaterThan(
            $numberOfSeats,
            0,
            'The number of seats for a concert should be greater than 0'
        );

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
        if ($this->date->equals($newDate)) {
            return;
        }

        if ($this->isCancelled) {
            throw CouldNotRescheduleConcert::becauseItWasAlreadyCancelled();
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

    public function makeReservation(
        ReservationId $reservationId,
        EmailAddress $emailAddress,
        int $numberOfSeats
    ): void {
        if ($numberOfSeats > $this->numberOfSeatsAvailable()) {
            throw CouldNotReserveSeats::becauseNotEnoughSeatsWereAvailable($numberOfSeats);
        }

        $this->reservations[] = new Reservation(
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
        foreach ($this->reservations as $key => $reservation) {
            if ($reservation->reservationId()->equals($reservationId)) {
                unset($this->reservations[$key]);
                $this->recordThat(
                    new ReservationWasCancelled(
                        $reservationId,
                        $this->concertId,
                        $reservation->numberOfSeats()
                    )
                );
                return;
            }
        }

        throw new RuntimeException(sprintf('Reservation "%s" not found', $reservationId->asString()));
    }

    public function numberOfSeatsAvailable(): int
    {
        $numberOfSeatsReserved = array_reduce(
            $this->reservations,
            function (int $total, Reservation $reservation): int {
                return $total + $reservation->numberOfSeats();
            },
            0
        );

        return $this->numberOfSeats - $numberOfSeatsReserved;
    }
}
