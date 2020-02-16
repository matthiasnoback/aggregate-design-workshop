<?php
declare(strict_types=1);

namespace TicketMill\Infrastructure;

use Ramsey\Uuid\Uuid;
use TicketMill\Domain\Model\Concert\ReservationId;
use TicketMill\Domain\Model\Concert\CouldNotFindReservation;
use TicketMill\Domain\Model\Reservation\Reservation;
use TicketMill\Domain\Model\Reservation\ReservationRepository;

final class InMemoryReservationRepository implements ReservationRepository
{
    /**
     * @var array&Reservation[]
     */
    private $reservations;

    public function getById(ReservationId $reservationId): Reservation
    {
        if (!isset($this->reservations[$reservationId->asString()])) {
            throw CouldNotFindReservation::withId($reservationId);
        }

        return $this->reservations[$reservationId->asString()];
    }

    public function nextIdentity(): ReservationId
    {
        return ReservationId::fromString(
            Uuid::uuid4()->toString()
        );
    }

    public function save(Reservation $reservation): void
    {
        $this->reservations[$reservation->reservationId()->asString()] = $reservation;
    }
}
