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
}
