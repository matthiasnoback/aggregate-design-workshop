<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

final class ReservationId
{
    /**
     * @var int
     */
    private $reservationId;

    private function __construct(int $reservationId)
    {
        $this->reservationId = $reservationId;
    }

    public static function fromInt(int $reservationId): self
    {
        return new self($reservationId);
    }

    public function asInt(): int
    {
        return $this->reservationId;
    }
}
