<?php

namespace TicketMill\Domain\Model\Reservation;

use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Concert\ConcertId;
use Utility\AggregateTestCase;

final class ReservationTest extends AggregateTestCase
{
    /**
     * @test
     */
    public function it_can_be_made_given_a_concert_id_an_email_address_and_a_number_of_seats(): void
    {
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
        $reservation = $this->aReservation();

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
        $cancelledReservation = $this->aReservation();
        $cancelledReservation->cancel();
        $cancelledReservation->releaseEvents();

        $cancelledReservation->cancel();

        self::assertArrayContainsObjectOfClass(
            ReservationWasCancelled::class,
            $cancelledReservation->releaseEvents(),
            0
        );
    }

    /**
     * @test
     */
    public function it_can_be_confirmed(): void
    {
        $reservation = $this->aReservation();

        $reservation->confirm();

        self::assertArrayContainsObjectOfClass(
            ReservationWasConfirmed::class,
            $reservation->releaseEvents()
        );
    }

    /**
     * @test
     */
    public function it_ignores_a_second_confirmation(): void
    {
        $confirmedReservation = $this->aReservation();
        $confirmedReservation->confirm();
        $confirmedReservation->releaseEvents();

        $confirmedReservation->confirm();

        self::assertArrayContainsObjectOfClass(
            ReservationWasConfirmed::class,
            $confirmedReservation->releaseEvents(),
            0
        );
    }

    private function aReservation(): Reservation
    {
        return Reservation::make(
            ReservationId::fromString('cd2514c8-ac19-4e1c-9a8c-1204782233d9'),
            ConcertId::fromString('ca1f570f-e314-4199-9abb-74177b6da280'),
            EmailAddress::fromString('test@example.com'),
            3
        );
    }
}
