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

        $concert->reschedule($sameDate = ScheduledDate::fromString($date));

        self::assertArrayContainsObjectOfClass(
            ConcertWasRescheduled::class,
            $concert->releaseEvents(),
            0
        );
    }

    /**
     * @test
     */
    public function we_can_reschedule_twice(): void
    {
        $date = '2021-09-01 20:00';
        $concert = $this->aConcertScheduledFor(
            $date
        );
        $anotherDate = ScheduledDate::fromString('2021-10-01 20:00');
        $concert->reschedule($anotherDate);
        $concert->releaseEvents();

        $concert->reschedule($originalDate = ScheduledDate::fromString($date));

        self::assertArrayContainsObjectOfClass(
            ConcertWasRescheduled::class,
            $concert->releaseEvents(),
            1
        );
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

        $aCancelledConcert->reschedule($anotherDate = ScheduledDate::fromString('2021-11-02 20:00'));
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

        self::assertArrayContainsObjectOfClass(
            ConcertWasCancelled::class,
            $concert->releaseEvents(),
            0
        );
    }

    /**
     * @test
     */
    public function it_can_process_a_reservation(): void
    {
        $concert = $this->aConcertWithNumberOfSeats(10);
        $concert->processReservation(2);

        self::assertEquals(8, $concert->numberOfSeatsAvailable());
    }

    /**
     * @test
     */
    public function it_can_process_a_reservation_cancellation(): void
    {
        $concert = $this->aConcertWithNumberOfSeats(10);
        $concert->processReservation(3);
        $concert->processReservation(2);
        $concert->processReservationCancellation(2);

        self::assertEquals(7, $concert->numberOfSeatsAvailable());
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

    private function aConcert(?int $numberOfSeats = null): Concert
    {
        return Concert::plan(
            $this->aConcertId(),
            $this->aName(),
            $this->aDate(),
            $numberOfSeats ?? $this->aNumberOfSeats()
        );
    }

    private function aConcertWithNumberOfSeats(int $numberOfSeats): Concert
    {
        return $this->aConcert($numberOfSeats);
    }

    private function aNumberOfSeats(): int
    {
        return 10;
    }
}
