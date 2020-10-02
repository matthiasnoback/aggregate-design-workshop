<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use Assert\Assertion;

final class ConcertId
{
    private string $id;

    private function __construct(string $id)
    {
        Assertion::uuid($id);
        $this->id = $id;
    }

    public static function fromString(string $id): ConcertId
    {
        return new self($id);
    }

    public function asString(): string
    {
        return $this->id;
    }
}
