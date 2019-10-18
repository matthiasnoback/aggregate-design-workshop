<?php
declare(strict_types=1);

namespace TicketMill\Infrastructure;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Application\CancelReservation;
use TicketMill\Application\MakeReservation;
use TicketMill\Application\Notifications\SendMail;
use TicketMill\Application\PlanConcert;
use TicketMill\Domain\Model\Concert\ConcertRepository;
use TicketMill\Domain\Model\Reservation\ReservationWasMade;
use TicketMill\Domain\Model\Reservation\ReservationRepository;

final class ServiceContainer
{
    public function planConcertService(): PlanConcert
    {
        return new PlanConcert($this->concertRepository(), $this->eventDispatcher());
    }

    public function makeReservationService(): MakeReservation
    {
        return new MakeReservation(
            $this->concertRepository(),
            $this->reservationRepository(),
            $this->eventDispatcher()
        );
    }

    public function cancelReservation(): CancelReservation
    {
        return new CancelReservation(
            $this->concertRepository(),
            $this->reservationRepository(),
            $this->eventDispatcher()
        );
    }

    private function eventDispatcher(): EventDispatcher
    {
        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->registerSubscriber(
            ReservationWasMade::class,
            [new SendMail($this->mailer()), 'whenReservationWasMade']
        );

        return $eventDispatcher;
    }

    private function concertRepository(): ConcertRepository
    {
        static $service;

        return $service ?? $service = new InMemoryConcertRepository();
    }

    private function reservationRepository(): ReservationRepository
    {
        static $service;

        return $service ?? $service = new InMemoryReservationRepository();
    }

    public function mailer(): MailerSpy
    {
        static $service;

        return $service ?? $service = new MailerSpy();
    }
}
