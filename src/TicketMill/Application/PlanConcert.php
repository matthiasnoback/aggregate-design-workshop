<?php
declare(strict_types=1);

namespace TicketMill\Application;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Domain\Model\ConcertId;
use TicketMill\Domain\Model\ConcertRepository;

final class PlanConcert
{
    /**
     * @var ConcertRepository
     */
    private $concertRepository;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

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
            $date,
            $numberOfSeats
        );

        $this->concertRepository->save($concert);

        $this->eventDispatcher->dispatchAll($concert->recordedEvents());
        $concert->clearEvents();
    }
}
