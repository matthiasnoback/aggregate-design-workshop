<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use Assert\Assertion;
use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Common\EventRecording;

final class Concert
{
    use EventRecording;

    /**
     * @var ConcertId
     */
    private $concertId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ScheduledDate
     */
    private $date;

    private function __construct()
    {
    }

    public static function plan(
        ConcertId $concertId,
        string $name,
        ScheduledDate $date,
        int $numberOfSeats
    ): Concert
    {
        $concert = new self();

        $concert->concertId = $concertId;

        Assertion::notEq($name, '', 'Name should not be empty');
        $concert->name = $name;

        $concert->date = $date;

        Assertion::greaterThan($numberOfSeats, 0, 'Number of seats should be greater than 0');

        return $concert;
    }

    public function concertId(): ConcertId
    {
        return $this->concertId;
    }

    public function reschedule(ScheduledDate $newDate): void
    {
    }

    public function cancel(): void
    {
    }

    public function makeReservation(EmailAddress $emailAddress, int $numberOfSeats): void
    {
    }

    public function numberOfSeatsAvailable(): int
    {
    }
}
