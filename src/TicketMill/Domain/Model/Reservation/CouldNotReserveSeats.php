<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Reservation;

use RuntimeException;

final class CouldNotReserveSeats extends RuntimeException
{
    public static function becauseNotEnoughSeatsWereAvailable(int $numberOfSeats): self
    {
        return new self(
            sprintf('Not enough seats were available to reserve %d seats', $numberOfSeats)
        );
    }
}
