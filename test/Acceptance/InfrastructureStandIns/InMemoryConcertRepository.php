<?php
declare(strict_types=1);

namespace Test\Acceptance\InfrastructureStandins;

use Ramsey\Uuid\Uuid;
use TicketMill\Domain\Model\Concert;
use TicketMill\Domain\Model\ConcertId;
use TicketMill\Domain\Model\ConcertRepository;
use TicketMill\Domain\Model\CouldNotFindConcert;

final class InMemoryConcertRepository implements ConcertRepository
{
    /**
     * @var array&Concert[]
     */
    private $concerts;

    /**
     * @throws CouldNotFindConcert
     */
    public function getById(ConcertId $concertId): Concert
    {
        if (!isset($this->concerts[$concertId->asString()])) {
            throw CouldNotFindConcert::withId($concertId);
        }

        return $this->concerts[$concertId->asString()];
    }

    public function nextIdentity(): ConcertId
    {
        return ConcertId::fromString(
            Uuid::uuid4()->toString()
        );
    }

    public function save(Concert $concert): void
    {
        $this->concerts[$concert->concertId()->asString()] = $concert;
    }
}
