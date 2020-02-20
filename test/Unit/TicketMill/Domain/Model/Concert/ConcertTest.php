<?php

namespace TicketMill\Domain\Model\Concert;

use InvalidArgumentException;
use Utility\AggregateTestCase;

final class ConcertTest extends AggregateTestCase
{
    /**
     * @test
     */
    public function it_requires_a_name_that_is_not_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('name');

        Concert::plan(
            $this->aConcertId(),
            $anEmptyName = '',
            $this->aDate(),
            $this->aNumberOfSeats()
        );
    }

    /**
     * @test
     */
    public function it_requires_a_positive_number_of_seats_available(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('seats');

        Concert::plan(
            $this->aConcertId(),
            $this->aName(),
            $this->aDate(),
            0
        );
    }

    /**
     * @test
     */
    public function it_can_be_rescheduled(): void
    {
        $concert = $this->aConcertScheduledFor('2020-09-01 20:00');

        $concert->reschedule($anotherDate = ScheduledDate::fromString('2021-10-01 20:00'));

        self::assertArrayContainsObjectOfClass(
            ConcertWasRescheduled::class,
            $concert->releaseEvents()
        );
    }

    /**
     * @test
     */
    public function rescheduling_to_the_same_date_has_no_effect(): void
    {
        $date = '2021-10-01 20:00';
        $concert = $this->aConcertScheduledFor(
            $date
        );
        $concert->releaseEvents();

        $concert->reschedule($sameDate = ScheduledDate::fromString($date));

        self::assertCount(0, $concert->releaseEvents());
    }

    /**
     * @test
     */
    public function it_can_not_be_rescheduled_when_it_has_been_cancelled(): void
    {
        $aCancelledConcert = $this->aConcert();
        $aCancelledConcert->cancel();

        $this->expectException(CouldNotRescheduleConcert::class);
        $this->expectExceptionMessage('cancelled');

        $aCancelledConcert->reschedule($anotherDate = ScheduledDate::fromString('2021-10-02 20:00'));
    }

    /**
     * @test
     */
    public function it_can_be_cancelled(): void
    {
        $concert = $this->aConcert();
        $concert->releaseEvents();

        $concert->cancel();

        self::assertArrayContainsObjectOfClass(
            ConcertWasCancelled::class,
            $concert->releaseEvents()
        );
    }

    /**
     * @test
     */
    public function cancelling_the_concert_twice_has_no_effect(): void
    {
        $concert = $this->aConcert();
        $concert->cancel();
        $concert->releaseEvents(); // the first time we cancel the concert, an event will be recorded

        $concert->cancel();

        self::assertCount(0, $concert->releaseEvents());
    }

    /**
     * @test
     */
    public function the_number_of_available_seats_is_initial_number_of_seats(): void
    {
        $concert = $this->aConcertWithNumberOfSeats(10);

        self::assertEquals(10, $concert->numberOfSeatsAvailable());
    }

    /**
     * @test
     */
    public function the_number_of_available_seats_can_be_increased_or_decrease(): void
    {
        $concert = $this->aConcertWithNumberOfSeats(10);

        $concert->increaseNumberOfAvailableSeats(5);

        self::assertEquals(15, $concert->numberOfSeatsAvailable());

        $concert->decreaseNumberOfAvailableSeats(3);

        self::assertEquals(12, $concert->numberOfSeatsAvailable());
    }

    private function aConcertId(): ConcertId
    {
        return ConcertId::fromString('de939fac-7777-449a-9360-b66f3cc3daec');
    }

    private function aName(): string
    {
        return 'Name';
    }

    private function aDate(): ScheduledDate
    {
        return ScheduledDate::fromString('2021-10-01 20:00');
    }

    private function aConcertScheduledFor(string $date): Concert
    {
        return Concert::plan(
            $this->aConcertId(),
            $this->aName(),
            ScheduledDate::fromString($date),
            $this->aNumberOfSeats()
        );
    }

    private function aConcert(): Concert
    {
        return Concert::plan(
            $this->aConcertId(),
            $this->aName(),
            $this->aDate(),
            $this->aNumberOfSeats()
        );
    }

    private function aNumberOfSeats(): int
    {
        return 10;
    }

    private function aConcertWithNumberOfSeats(int $numberOfSeats): Concert
    {
        return Concert::plan(
            $this->aConcertId(),
            $this->aName(),
            $this->aDate(),
            $numberOfSeats
        );
    }
}
