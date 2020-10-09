<?php

namespace TicketMill\Domain\Model\Concert;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TicketMill\Domain\Model\Reservation\ReservationId;

final class ReservationIdTest extends TestCase
{
    /**
     * @test
     */
    public function it_requires_a_valid_uuid_when_created_from_a_string(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ReservationId::fromString('not-a-uuid');
    }

    /**
     * @test
     */
    public function it_can_be_created_from_a_string_and_converted_back_to_a_string(): void
    {
        $id = 'de939fac-7777-449a-9360-b66f3cc3daec';

        self::assertEquals(
            $id,
            ReservationId::fromString($id)->asString()
        );
    }
}
