<?php
declare(strict_types=1);

namespace TicketMill\Infrastructure;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Application\CancelReservation;
use TicketMill\Application\ConfirmReservation;
use TicketMill\Application\MakeReservation;
use TicketMill\Application\Notifications\SendMail;
use TicketMill\Application\PlanConcert;
use TicketMill\Application\ProcessReservation;
use TicketMill\Domain\Model\Concert\ConcertRepository;
use TicketMill\Domain\Model\Concert\ReservationWasAccepted;
use TicketMill\Domain\Model\Reservation\ReservationWasCancelled;
use TicketMill\Domain\Model\Reservation\ReservationWasConfirmed;
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
            $this->reservationRepository(),
            $this->eventDispatcher()
        );
    }

    private function eventDispatcher(): EventDispatcher
    {
        $eventDispatcher = new EventDispatcher();

        $processReservation = new ProcessReservation($this->concertRepository(), $eventDispatcher);
        $eventDispatcher->registerSubscriber(
            ReservationWasMade::class,
            [$processReservation, 'whenReservationWasMade']
        );
        $eventDispatcher->registerSubscriber(
            ReservationWasCancelled::class,
            [$processReservation, 'whenReservationWasCancelled']
        );

        $confirmReservation = new ConfirmReservation($this->reservationRepository(), $eventDispatcher);
        $eventDispatcher->registerSubscriber(
            ReservationWasAccepted::class,
            [$confirmReservation, 'whenReservationWasAccepted']
        );

        $eventDispatcher->registerSubscriber(
            ReservationWasConfirmed::class,
            [new SendMail($this->mailer()), 'whenReservationWasConfirmed']
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
