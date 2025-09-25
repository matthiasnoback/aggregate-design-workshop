<?php

namespace TicketMill\Domain\Model\Concert;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TicketMill\Domain\Model\Reservation\ReservationId;

final class ReservationIdTest extends TestCase
{
    public function testItRequiresAValidUuidWhenCreatedFromAString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ReservationId::fromString('not-a-uuid');
    }

    public function testItCanBeCreatedFromAStringAndConvertedBackToAString(): void
    {
        $id = 'de939fac-7777-449a-9360-b66f3cc3daec';

        self::assertEquals(
            $id,
            ReservationId::fromString($id)->asString()
        );
    }

    public function testItCanBeComparedToAnotherId(): void
    {
        $id1 = ReservationId::fromString('de939fac-7777-449a-9360-b66f3cc3daec');
        $id2 = ReservationId::fromString('49ee0aed-6d70-46e2-91e8-01a7488f21b9');
        self::assertTrue(
            $id1->equals($id1)
        );
        self::assertFalse(
            $id1->equals($id2)
        );
    }
}
