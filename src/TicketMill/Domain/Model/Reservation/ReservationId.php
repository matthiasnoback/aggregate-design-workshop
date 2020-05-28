<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Reservation;

use Assert\Assertion;

final class ReservationId
{
    /**
     * @var string
     */
    private $id;

    private function __construct(string $id)
    {
        Assertion::uuid($id);
        $this->id = $id;
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function asString(): string
    {
        return $this->id;
    }
}
