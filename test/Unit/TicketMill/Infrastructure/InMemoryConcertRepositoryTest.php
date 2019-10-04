<?php

namespace TicketMill\Infrastructure;

use PHPUnit\Framework\TestCase;
use TicketMill\Domain\Model\Concert\Concert;
use TicketMill\Domain\Model\Concert\ConcertId;
use TicketMill\Domain\Model\Concert\ScheduledDate;

final class InMemoryConcertRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_save_and_load_a_concert(): void
    {
        $repository = new InMemoryConcertRepository();

        $concertId = $repository->nextIdentity();
        $concert = $this->createConcert($concertId);

        $repository->save($concert);

        $fromRepository = $repository->getById($concertId);

        self::assertEquals($concert, $fromRepository);
    }

    private function createConcert(ConcertId $concertId): Concert
    {
        return Concert::plan(
            $concertId,
            'Name',
            ScheduledDate::fromString('2021-10-01 20:00'),
            10
        );
    }
}
