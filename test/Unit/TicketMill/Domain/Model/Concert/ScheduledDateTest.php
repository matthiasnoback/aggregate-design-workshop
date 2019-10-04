<?php

namespace TicketMill\Domain\Model\Concert;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ScheduledDateTest extends TestCase
{
    /**
     * @test
     */
    public function it_requires_a_string_with_the_correct_format_to_be_used(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ScheduledDate::fromString('incorrect-format');
    }

    /**
     * @test
     */
    public function it_can_be_created_from_a_string_and_converted_back_to_it(): void
    {
        $date = '2020-09-01 20:00';

        self::assertEquals(
            $date,
            ScheduledDate::fromString($date)->asString()
        );
    }

    /**
     * @test
     */
    public function it_can_be_compared_with_other_instances(): void
    {
        self::assertTrue(
            ScheduledDate::fromString('2020-09-01 20:00')->equals(
                ScheduledDate::fromString($sameDate = '2020-09-01 20:00')
            )
        );

        self::assertFalse(
            ScheduledDate::fromString('2020-09-01 20:00')->equals(
                ScheduledDate::fromString($otherDate = '2021-10-01 20:00')
            )
        );
    }
}
