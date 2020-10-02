<?php
declare(strict_types=1);

namespace TicketMill\Application;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Domain\Model\Concert\Concert;
use TicketMill\Domain\Model\Concert\ConcertId;
use TicketMill\Domain\Model\Concert\ConcertRepository;
use TicketMill\Domain\Model\Concert\ScheduledDate;

final class PlanConcert
{
    private ConcertRepository $concertRepository;
    private EventDispatcher $eventDispatcher;

    public function __construct(
        ConcertRepository $concertRepository,
        EventDispatcher $eventDispatcher
    ) {
        $this->concertRepository = $concertRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function plan(
        string $name,
        string $date,
        int $numberOfSeats
    ): ConcertId {
        $concert = Concert::plan(
            $this->concertRepository->nextIdentity(),
            $name,
            ScheduledDate::fromString($date),
            $numberOfSeats
        );

        $this->concertRepository->save($concert);

        $this->eventDispatcher->dispatchAll($concert->releaseEvents());

        return $concert->concertId();
    }
}
