<?php

namespace TicketMill\Domain\Model\Reservation;

use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Concert\ConcertId;
use TicketMill\Domain\Model\Concert\ReservationId;
use TicketMill\Domain\Model\Concert\ReservationWasCancelled;
use TicketMill\Domain\Model\Concert\ReservationWasMade;
use Utility\AggregateTestCase;

final class ReservationTest extends AggregateTestCase
{
    /**
     * @test
     */
    public function it_can_be_made_given_a_concert_id_an_email_address_and_a_number_of_seats(): void
    {
        $this->markTestIncomplete('Assignment 5');

        $reservation = Reservation::make(
            ReservationId::fromString('cd2514c8-ac19-4e1c-9a8c-1204782233d9'),
            ConcertId::fromString('ca1f570f-e314-4199-9abb-74177b6da280'),
            EmailAddress::fromString('test@example.com'),
            3
        );

        self::assertArrayContainsObjectOfClass(
            ReservationWasMade::class,
            $reservation->releaseEvents()
        );
    }

    /**
     * @test
     */
    public function it_can_be_cancelled(): void
    {
        $this->markTestIncomplete('Assignment 5');

        $reservation = Reservation::make(
            ReservationId::fromString('cd2514c8-ac19-4e1c-9a8c-1204782233d9'),
            ConcertId::fromString('ca1f570f-e314-4199-9abb-74177b6da280'),
            EmailAddress::fromString('test@example.com'),
            3
        );

        $reservation->cancel();

        self::assertArrayContainsObjectOfClass(
            ReservationWasCancelled::class,
            $reservation->releaseEvents()
        );
    }

    /**
     * @test
     */
    public function cancelling_it_twice_has_no_effect(): void
    {
        $this->markTestIncomplete('Assignment 5');

        $cancelledReservation = Reservation::make(
            ReservationId::fromString('cd2514c8-ac19-4e1c-9a8c-1204782233d9'),
            ConcertId::fromString('ca1f570f-e314-4199-9abb-74177b6da280'),
            EmailAddress::fromString('test@example.com'),
            3
        );
        $cancelledReservation->cancel();
        $cancelledReservation->releaseEvents();

        $cancelledReservation->cancel();

        self::assertArrayContainsObjectOfClass(
            ReservationWasCancelled::class,
            $cancelledReservation->releaseEvents(),
            0
        );
    }
}
