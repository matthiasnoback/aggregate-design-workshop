<?php

namespace TicketMill\Domain\Model\Concert;

use InvalidArgumentException;
use TicketMill\Domain\Model\Common\EmailAddress;
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
        $this->markTestIncomplete('Assignment 2');

        $concert = $this->aConcertScheduledFor('2020-09-01 20:00');

        // TODO: Verify that the concert has indeed been rescheduled
        $concert->reschedule($anotherDate = ScheduledDate::fromString('2021-10-01 20:00'));
    }

    /**
     * @test
     */
    public function rescheduling_to_the_same_date_has_no_effect(): void
    {
        $this->markTestIncomplete('Assignment 2');

        $date = '2021-10-01 20:00';
        $concert = $this->aConcertScheduledFor(
            $date
        );

        // TODO: Verify that nothing has changed
        $concert->reschedule($sameDate = ScheduledDate::fromString($date));
    }

    /**
     * @test
     */
    public function it_can_not_be_rescheduled_when_it_has_been_cancelled(): void
    {
        $this->markTestIncomplete('Assignment 3');

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
        $this->markTestIncomplete('Assignment 3');

        $concert = $this->aConcert();
        $concert->releaseEvents();

        $concert->cancel();

        // TODO: Verify that the concert has indeed been cancelled
    }

    /**
     * @test
     */
    public function cancelling_the_concert_twice_has_no_effect(): void
    {
        $this->markTestIncomplete('Assignment 3');

        $concert = $this->aConcert();
        $concert->cancel();
        $concert->releaseEvents(); // the first time we cancel the concert, an event will be recorded

        $concert->cancel();

        self::assertCount(0, $concert->releaseEvents());
    }

    /**
     * @test
     */
    public function you_can_reserve_seats_for_a_concert(): void
    {
        $this->markTestIncomplete('Assignment 4');

        $concert = $this->concertWithNumberOfSeatsAvailable(10);

        $concert->makeReservation($this->anEmailAddress(), 3);

        self::assertArrayContainsObjectOfClass(
            ReservationWasMade::class,
            $concert->releaseEvents()
        );
        self::assertEquals(7, $concert->numberOfSeatsAvailable());
    }

    /**
     * @test
     */
    public function you_can_not_reserve_more_seats_for_a_concert_than_there_are_seats(): void
    {
        $this->markTestIncomplete('Assignment 4');

        $concert = $this->concertWithNumberOfSeatsAvailable(10);

        $this->expectException(CouldNotReserveSeats::class);
        $this->expectExceptionMessage('Not enough seats were available');

        $concert->makeReservation($this->anEmailAddress(), $moreThanAvailable = 11);
    }

    /**
     * @test
     */
    public function you_can_not_reserve_more_seats_for_a_concert_than_there_are_seats_available(): void
    {
        $this->markTestIncomplete('Assignment 4');

        $concert = $this->concertWithNumberOfSeatsAvailable(10);
        $concert->makeReservation($this->anEmailAddress(), 7);
        self::assertEquals(3, $concert->numberOfSeatsAvailable());

        $this->expectException(CouldNotReserveSeats::class);
        $this->expectExceptionMessage('Not enough seats were available');

        $concert->makeReservation($this->anEmailAddress(), $moreThanAvailable = 6);
    }

    /**
     * @test
     */
    public function cancelling_a_reservation_makes_its_seats_available_again(): void
    {
        $this->markTestIncomplete('Assignment 4');

        $concert = $this->concertWithNumberOfSeatsAvailable(10);
        $concert->makeReservation($this->anEmailAddress(), 4);
        $reservationId = $concert->makeReservation($this->anEmailAddress(), 3);

        $concert->cancelReservation($reservationId);

        self::assertEquals(10 - 4, $concert->numberOfSeatsAvailable());
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

    private function anEmailAddress(): EmailAddress
    {
        return EmailAddress::fromString('test@example.com');
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
}
