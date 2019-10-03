<?php

namespace Test\Acceptance\InfrastructureStandIns;

use PHPUnit\Framework\TestCase;
use TicketMill\Domain\Model\Concert;

final class InMemoryConcertRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_save_and_load_a_concert(): void
    {
        $repository = new InMemoryConcertRepository();

        $concertId = $repository->nextIdentity();
        $concert = new Concert($concertId);

        $repository->save($concert);

        $fromRepository = $repository->getById($concertId);

        self::assertEquals($concert, $fromRepository);
    }
}
