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
        $concert->processReservation(2, $this->aReservationId());

        self::assertArrayContainsObjectOfClass(
            ReservationWasAccepted::class,
            $concert->releaseEvents()
        );
    }

    /**
     * @test
     */
    public function it_rejects_a_reservation_if_the_number_of_seats_exceeds_the_number_of_available_seats(): void
    {
        $concert = $this->aConcertWithNumberOfSeats(10);
        $concert->processReservation(12, $this->aReservationId());

        self::assertArrayContainsObjectOfClass(
            ReservationWasRejected::class,
            $concert->releaseEvents()
        );
    }

    /**
     * @test
     */
    public function it_rejects_the_first_reservation_that_exceeds_the_number_of_available_seats(): void
    {
        $concert = $this->aConcertWithNumberOfSeats(10);
        $concert->processReservation(8, $this->aReservationId());
        $concert->releaseEvents();

        $concert->processReservation(3, $this->aReservationId());

        self::assertArrayContainsObjectOfClass(
            ReservationWasRejected::class,
            $concert->releaseEvents()
        );
    }

    /**
     * @test
     */
    public function it_can_process_a_reservation_cancellation(): void
    {
        $concert = $this->aConcertWithNumberOfSeats(10);
        $concert->processReservation(3, $this->aReservationId());
        $concert->processReservation(2, $this->aReservationId());
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

    private function aReservationId(): ReservationId
    {
        return ReservationId::fromString('10b6edf2-1c74-4faf-925d-51991d73cc41');
    }
}
