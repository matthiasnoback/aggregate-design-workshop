<?php

declare(strict_types=1);

namespace TicketMill\Application;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Concert\ConcertId;
use TicketMill\Domain\Model\Concert\ConcertRepository;
use TicketMill\Domain\Model\Concert\CouldNotReserveSeats;
use TicketMill\Domain\Model\Concert\ReservationId;
use TicketMill\Domain\Model\Reservation\Reservation;
use TicketMill\Domain\Model\Reservation\ReservationRepository;

final readonly class MakeReservation
{
    public function __construct(
        private ConcertRepository $concertRepository,
        private ReservationRepository $reservationRepository,
        private EventDispatcher $eventDispatcher
    ) {
    }

    public function makeReservation(string $concertId, string $emailAddress, int $numberOfSeats): ReservationId
    {
        $concert = $this->concertRepository->getById(ConcertId::fromString($concertId));

        $reservationId = $this->reservationRepository->nextIdentity();

        if ($concert->numberOfSeatsAvailable() < $numberOfSeats) {
            throw CouldNotReserveSeats::becauseNotEnoughSeatsWereAvailable($numberOfSeats);
        }

        $reservation = Reservation::make(
            $reservationId,
            $concert->concertId(),
            EmailAddress::fromString($emailAddress),
            $numberOfSeats
        );

        $this->reservationRepository->save($reservation);

        $this->eventDispatcher->dispatchAll($reservation->releaseEvents());

        return $reservationId;
    }
}
