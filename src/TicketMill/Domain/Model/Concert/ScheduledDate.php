<?php

declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use DateTimeImmutable;
use InvalidArgumentException;

final readonly class ScheduledDate
{
    private string $date;

    private function __construct(string $date)
    {
        $result = DateTimeImmutable::createFromFormat('Y-m-d H:i', $date);
        if ($result === false) {
            throw new InvalidArgumentException('The provided date does not match the expected format');
        }

        $this->date = $date;
    }

    public static function fromString(string $date): self
    {
        return new self($date);
    }

    public function asString(): string
    {
        return $this->date;
    }

    public function equals(self $other): bool
    {
        return $this->date === $other->date;
    }
}
