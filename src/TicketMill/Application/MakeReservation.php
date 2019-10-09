<?php
declare(strict_types=1);

namespace TicketMill\Application;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Domain\Model\Concert\ConcertId;
use TicketMill\Domain\Model\Concert\ConcertRepository;
use TicketMill\Domain\Model\Common\EmailAddress;
use TicketMill\Domain\Model\Reservation\CouldNotReserveSeats;
use TicketMill\Domain\Model\Reservation\Reservation;
use TicketMill\Domain\Model\Reservation\ReservationRepository;

final class MakeReservation
{
    /**
     * @var ConcertRepository
     */
    private $concertRepository;

    /**
     * @var ReservationRepository
     */
    private $reservationRepository;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(
        ConcertRepository $concertRepository,
        ReservationRepository $reservationRepository,
        EventDispatcher $eventDispatcher
    ) {
        $this->concertRepository = $concertRepository;
        $this->reservationRepository = $reservationRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function makeReservation(string $concertId, string $emailAddress, int $numberOfSeats): void
    {
        $concert = $this->concertRepository->getById(ConcertId::fromString($concertId));
        if ($concert->numberOfSeatsAvailable() < $numberOfSeats) {
            throw CouldNotReserveSeats::becauseNotEnoughSeatsWereAvailable($numberOfSeats);
        }

        $reservation = Reservation::make(
            $this->reservationRepository->nextIdentity(),
            $concert->concertId(),
            EmailAddress::fromString($emailAddress),
            $numberOfSeats
        );

        $this->reservationRepository->save($reservation);

        $this->eventDispatcher->dispatchAll($reservation->releaseEvents());
    }
}
