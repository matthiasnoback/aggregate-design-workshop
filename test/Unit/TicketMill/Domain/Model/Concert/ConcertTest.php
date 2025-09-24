<?php

namespace TicketMill\Domain\Model\Concert;

use InvalidArgumentException;
use RuntimeException;
use TicketMill\Domain\Model\Common\EmailAddress;
use Utility\AggregateTestCase;

final class ConcertTest extends AggregateTestCase
{
    public function testItRequiresANameThatIsNotEmpty(): void
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

    public function testItRequiresAPositiveNumberOfSeatsAvailable(): void
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

    public function testItCanBeRescheduled(): void
    {
        $concert = $this->aConcertScheduledFor('2020-09-01 20:00');

        $concert->reschedule($anotherDate = ScheduledDate::fromString('2021-10-01 20:00'));

        self::assertArrayContainsObjectOfClass(
            ConcertWasRescheduled::class,
            $concert->releaseEvents(),
            1
        );
    }

    public function testReschedulingToTheSameDateHasNoEffect(): void
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

    public function testItCanNotBeRescheduledWhenItHasBeenCancelled(): void
    {
        $aCancelledConcert = $this->aConcert();
        $aCancelledConcert->cancel();

        $this->expectException(CouldNotRescheduleConcert::class);
        $this->expectExceptionMessage('cancelled');

        $aCancelledConcert->reschedule($anotherDate = ScheduledDate::fromString('2021-11-02 20:00'));
    }

    public function testItCanBeCancelled(): void
    {
        $concert = $this->aConcert();
        $concert->releaseEvents();

        $concert->cancel();

        self::assertArrayContainsObjectOfClass(
            ConcertWasCancelled::class,
            $concert->releaseEvents()
        );
    }

    public function testCancellingTheConcertTwiceHasNoEffect(): void
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

    public function testYouCanReserveSeatsForAConcert(): void
    {
        $this->markTestIncomplete('Assignment 4');

        $concert = $this->concertWithNumberOfSeatsAvailable(10);

        $concert->makeReservation($this->aReservationId(), $this->anEmailAddress(), 3);

        self::assertArrayContainsObjectOfClass(
            ReservationWasMade::class,
            $concert->releaseEvents()
        );
        self::assertEquals(7, $concert->numberOfSeatsAvailable());
    }

    public function testYouCanNotReserveMoreSeatsForAConcertThanThereAreSeats(): void
    {
        $this->markTestIncomplete('Assignment 4');

        $concert = $this->concertWithNumberOfSeatsAvailable(10);

        $this->expectException(CouldNotReserveSeats::class);
        $this->expectExceptionMessage('Not enough seats were available');

        $concert->makeReservation($this->aReservationId(), $this->anEmailAddress(), $moreThanAvailable = 11);
    }

    public function testYouCanNotReserveMoreSeatsForAConcertThanThereAreSeatsAvailable(): void
    {
        $this->markTestIncomplete('Assignment 4');

        $concert = $this->concertWithNumberOfSeatsAvailable(10);
        $concert->makeReservation($this->aReservationId(), $this->anEmailAddress(), 7);
        self::assertEquals(3, $concert->numberOfSeatsAvailable());

        $this->expectException(CouldNotReserveSeats::class);
        $this->expectExceptionMessage('Not enough seats were available');

        $concert->makeReservation($this->anotherReservationId(), $this->anEmailAddress(), $moreThanAvailable = 6);
    }

    public function testCancellingAReservationMakesItsSeatsAvailableAgain(): void
    {
        $this->markTestIncomplete('Assignment 4');

        $concert = $this->concertWithNumberOfSeatsAvailable(10);
        $concert->makeReservation($this->aReservationId(), $this->anEmailAddress(), 4);
        $reservationId = $this->anotherReservationId();
        $concert->makeReservation($reservationId, $this->anEmailAddress(), 3);

        $concert->cancelReservation($reservationId);

        self::assertArrayContainsObjectOfClass(
            ReservationWasCancelled::class,
            $concert->releaseEvents()
        );

        self::assertEquals(10 - 4, $concert->numberOfSeatsAvailable());
    }

    public function testItWillFailToCancelAReservationIfTheReservationDoesNotExist(): void
    {
        $this->markTestIncomplete('Assignment 4');

        $concert = $this->aConcert();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not find');

        // No reservations have been made, so reservation 1 does not exist
        $concert->cancelReservation($this->aReservationId());
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

    private function aReservationId(): ReservationId
    {
        return ReservationId::fromString('48ebab9c-1be8-42e5-b87a-6adda38d9116');
    }

    private function anotherReservationId(): ReservationId
    {
        return ReservationId::fromString('dc5998cb-34fa-4589-a4a1-33f3f76a812a');
    }
}
