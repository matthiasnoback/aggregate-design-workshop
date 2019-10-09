<?php

namespace TicketMill\Domain\Model\Concert;

use InvalidArgumentException;
use TicketMill\Domain\Model\Reservation\ReservationId;
use Utility\AggregateTestCase;

final class ConcertTest extends AggregateTestCase
{
    /**
     * @test
     */
    public function it_requires_a_name_that_is_not_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Name');

        Concert::plan(
            $this->aConcertId(),
            $anEmptyName = '',
            $this->aDate(),
            10
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

        $aCancelledConcert->reschedule($anotherDate = ScheduledDate::fromString('2021-10-01 20:00'));
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
    public function it_will_accept_a_reservation_when_a_sufficient_number_of_seats_is_available(): void
    {
        $concert = $this->concertWithNumberOfSeatsAvailable(10);

        $concert->processReservation($this->aReservationId(), 2);

        self::assertArrayContainsObjectOfClass(
            ReservationWasAccepted::class,
            $concert->releaseEvents()
        );
    }

    /**
     * @test
     */
    public function it_will_not_accept_a_reservation_when_an_insufficient_number_of_seats_is_available(): void
    {
        $concert = $this->concertWithNumberOfSeatsAvailable(10);

        $concert->processReservation($this->aReservationId(), 12);

        self::assertArrayContainsObjectOfClass(
            ReservationWasRejected::class,
            $concert->releaseEvents()
        );
    }

    private function aConcertId(): ConcertId
    {
        return ConcertId::fromString('de939fac-7777-449a-9360-b66f3cc3daec');
    }

    private function aName(): string
    {
        return 'Name';
    }

    private function concertWithNumberOfSeatsAvailable(int $numberOfSeats): Concert
    {
        return Concert::plan(
            $this->aConcertId(),
            $this->aName(),
            $this->aDate(),
            $numberOfSeats
        );
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

    private function aReservationId(): ReservationId
    {
        return ReservationId::fromString('e4186a82-e134-489a-bef4-aed9864d8aea');
    }
}
