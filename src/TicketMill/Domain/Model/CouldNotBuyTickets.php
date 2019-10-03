<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model;

use RuntimeException;

final class CouldNotBuyTickets extends RuntimeException
{
    public static function becauseNotEnoughSeatsWereAvailable(int $numberOfTickets): self
    {
        return new self(
            sprintf('Not enough seats were available to buy %d tickets', $numberOfTickets)
        );
    }
}
