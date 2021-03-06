<?php

namespace TicketMill\Domain\Model\Concert;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ConcertIdTest extends TestCase
{
    /**
     * @test
     */
    public function it_requires_a_valid_uuid_when_created_from_a_string(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ConcertId::fromString('not-a-uuid');
    }

    /**
     * @test
     */
    public function it_can_be_created_from_a_string_and_converted_back_to_a_string(): void
    {
        $id = 'de939fac-7777-449a-9360-b66f3cc3daec';

        self::assertEquals(
            $id,
            ConcertId::fromString($id)->asString()
        );
    }

    /**
     * @test
     */
    public function it_can_be_compared_to_another_id(): void
    {
        $id1 = ConcertId::fromString('de939fac-7777-449a-9360-b66f3cc3daec');
        $id2 = ConcertId::fromString('49ee0aed-6d70-46e2-91e8-01a7488f21b9');
        self::assertTrue(
            $id1->equals($id1)
        );
        self::assertFalse(
            $id1->equals($id2)
        );
    }
}
