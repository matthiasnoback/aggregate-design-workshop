<?php

namespace TicketMill\Infrastructure;

use PHPUnit\Framework\TestCase;
use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Concert\ConcertId;
use TicketMill\Domain\Model\Reservation\Reservation;
use TicketMill\Domain\Model\Reservation\ReservationId;

final class InMemoryReservationRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_save_and_load_a_reservation(): void
    {
        $repository = new InMemoryReservationRepository();

        $reservationId = $repository->nextIdentity();
        $reservation = $this->createReservation($reservationId);

        $repository->save($reservation);

        $fromRepository = $repository->getById($reservationId);

        self::assertEquals($reservation, $fromRepository);
    }

    private function createReservation(ReservationId $reservationId): Reservation
    {
        return Reservation::make(
            $reservationId,
            ConcertId::fromString('de939fac-7777-449a-9360-b66f3cc3daec'),
            EmailAddress::fromString('test@example.com'),
            3
        );
    }
}
