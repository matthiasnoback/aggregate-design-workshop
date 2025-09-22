<?php

namespace TicketMill\Domain\Model\Concert;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ScheduledDateTest extends TestCase
{
    public function testItRequiresAStringWithTheCorrectFormatToBeUsed(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ScheduledDate::fromString('incorrect-format');
    }

    public function testItCanBeCreatedFromAStringAndConvertedBackToIt(): void
    {
        $date = '2020-09-01 20:00';

        self::assertEquals(
            $date,
            ScheduledDate::fromString($date)->asString()
        );
    }

    public function testItCanBeComparedWithOtherInstances(): void
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
