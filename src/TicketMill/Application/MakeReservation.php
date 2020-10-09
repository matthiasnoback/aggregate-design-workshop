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

final class MakeReservation
{
    private ConcertRepository $concertRepository;
    private EventDispatcher $eventDispatcher;
    private ReservationRepository $reservationRepository;

    public function __construct(
        ConcertRepository $concertRepository,
        ReservationRepository $reservationRepository,
        EventDispatcher $eventDispatcher
    ) {
        $this->concertRepository = $concertRepository;
        $this->reservationRepository = $reservationRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function makeReservation(string $concertId, string $emailAddress, int $numberOfSeats): ReservationId
    {
        $concert = $this->concertRepository->getById(ConcertId::fromString($concertId));

        if ($numberOfSeats > $concert->numberOfSeatsAvailable()) {
            throw CouldNotReserveSeats::becauseNotEnoughSeatsWereAvailable($numberOfSeats);
        }

        $reservationId = $this->reservationRepository->nextIdentity();

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
