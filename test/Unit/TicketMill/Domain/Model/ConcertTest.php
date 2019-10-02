<?php

namespace TicketMill\Domain\Model;

use InvalidArgumentException;
use Utility\AggregateTestCase;

final class ConcertTest extends AggregateTestCase
{
    /**
     * @test
     */
    public function it_requires_a_name_that_is_not_empty(): void
    {
        $this->markTestIncomplete('Assignment 1');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('name');

        new Concert(
            $this->aConcertId(),
            $this->aDate(),
            $anEmptyName = '',
            10
        );
    }

    /**
     * @test
     */
    public function it_requires_a_positive_number_of_seats_available(): void
    {
        $this->markTestIncomplete('Assignment 1');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('seats');

        new Concert(
            $this->aConcertId(),
            $this->aDate(),
            $this->aName(),
            0
        );
    }

    /**
     * @test
     */
    public function it_can_be_rescheduled(): void
    {
        $this->markTestIncomplete('Assignment 2');

        $concert = $this->aConcertScheduledFor('2021-10-01 20:00');

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
    public function cancelling_the_concert_twice_has_no_effect(): void
    {
        $this->markTestIncomplete('Assignment 3');

        $concert = $this->aConcert();
        $concert->cancel();
        $concert->clearEvents(); // the first time we cancel the concert, an event will be recorded

        $concert->cancel($anotherDate = ScheduledDate::fromString('2021-10-01 20:00'));

        self::assertCount(0, $concert->recordedEvents());
    }

    /**
     * @test
     */
    public function you_can_buy_tickets_for_a_concert(): void
    {
        $this->markTestIncomplete('Assignment 4');

        $concert = $this->concertWithNumberOfSeatsAvailable(10);

        $concert->buyTickets($this->anEmailAddress(), 3);

        self::assertArrayContainsObjectOfClass(
            TicketWasBought::class,
            $concert->recordedEvents(),
            3
        );
    }

    /**
     * @test
     */
    public function you_can_not_buy_more_tickets_for_a_concert_than_there_are_seats(): void
    {
        $this->markTestIncomplete('Assignment 4');

        $concert = $this->concertWithNumberOfSeatsAvailable(10);

        $this->expectException(CouldNotBuyTickets::class);
        $this->expectExceptionMessage('not enough seats available');

        $concert->buyTickets($this->anEmailAddress(), $moreThanAvailable = 11);
    }

    /**
     * @test
     */
    public function you_can_not_buy_more_tickets_for_a_concert_than_there_are_seats_available(): void
    {
        $this->markTestIncomplete('Assignment 4');

        $concert = $this->concertWithNumberOfSeatsAvailable(10);
        $concert->buyTickets($this->anEmailAddress(), 7);

        $this->expectException(CouldNotBuyTickets::class);
        $this->expectExceptionMessage('not enough seats available');

        $concert->buyTickets($this->anEmailAddress(), $moreThanAvailable = 6);
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
            $this->aDate(),
            $this->aName(),
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
            ScheduledDate::fromString($date),
            $this->aName(),
            $this->aNumberOfSeats()
        );
    }

    private function aConcert(): Concert
    {
        return Concert::plan(
            $this->aConcertId(),
            $this->aDate(),
            $this->aName(),
            $this->aNumberOfSeats()
        );
    }

    private function aNumberOfSeats(): int
    {
        return 10;
    }
}
