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
    public function testItCanBeMadeGivenAConcertIdAnEmailAddressAndANumberOfSeats(): void
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

    public function testItCanBeCancelled(): void
    {
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

    public function testCancellingItTwiceHasNoEffect(): void
    {
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
