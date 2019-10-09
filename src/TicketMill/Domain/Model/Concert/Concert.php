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

    private function __construct(
        ConcertId $concertId,
        string $name,
        ScheduledDate $date,
        int $numberOfSeats
    ) {
        $this->concertId = $concertId;

        Assertion::notEq($name, '', 'Name should not be empty');
        $this->name = $name;

        $this->date = $date;

        Assertion::greaterThan($numberOfSeats, 0, 'Number of seats should be greater than 0');
    }

    public static function plan(
        ConcertId $concertId,
        string $name,
        ScheduledDate $date,
        int $numberOfSeats
    ): Concert {
        return new self(
            $concertId,
            $name,
            $date,
            $numberOfSeats
        );
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
