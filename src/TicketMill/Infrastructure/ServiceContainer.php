<?php
declare(strict_types=1);

namespace TicketMill\Infrastructure;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Application\CancelReservation;
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
    private ?EventDispatcher $eventDispatcher = null;
    private ?ConcertRepository $concertRepository = null;
    private ?ReservationRepository $reservationRepository = null;
    private ?MailerSpy $mailer = null;
    private ?EventSubscriberSpy $eventSubscriberSpy = null;

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
        return new CancelReservation($this->reservationRepository(), $this->eventDispatcher());
    }

    private function eventDispatcher(): EventDispatcher
    {
        if ($this->eventDispatcher === null) {
            $this->eventDispatcher = new EventDispatcher();
            $this->eventDispatcher->subscribeToAllEvents(
                function (object $event): void {
                    echo get_class($event) . "\n";
                }
            );
            $this->eventDispatcher->subscribeToAllEvents(
                $this->eventSubscriberSpy()
            );
            $this->eventDispatcher->registerSubscriber(
                ReservationWasConfirmed::class,
                [new SendMail($this->mailer()), 'whenReservationWasConfirmed']
            );
            $this->eventDispatcher->registerSubscriber(
                ReservationWasMade::class,
                [new ProcessReservation($this->concertRepository(), $this->reservationRepository(), $this->eventDispatcher()), 'whenReservationWasMade']
            );
            $this->eventDispatcher->registerSubscriber(
                ReservationWasCancelled::class,
                [new ProcessReservation($this->concertRepository(), $this->reservationRepository(), $this->eventDispatcher()), 'whenReservationWasCancelled']
            );
            $this->eventDispatcher->registerSubscriber(
                ReservationWasAccepted::class,
                [new ProcessReservation($this->concertRepository(), $this->reservationRepository(), $this->eventDispatcher()), 'whenReservationWasAccepted']
            );
        }

        return $this->eventDispatcher;
    }

    private function concertRepository(): ConcertRepository
    {
        if ($this->concertRepository === null) {
            $this->concertRepository = new InMemoryConcertRepository();
        }

        return $this->concertRepository;
    }

    private function reservationRepository(): ReservationRepository
    {
        if ($this->reservationRepository === null) {
            $this->reservationRepository = new InMemoryReservationRepository();
        }

        return $this->reservationRepository;
    }

    public function mailer(): MailerSpy
    {
        if ($this->mailer === null) {
            $this->mailer = new MailerSpy();
        }

        return $this->mailer;
    }

    private function eventSubscriberSpy(): EventSubscriberSpy
    {
        if ($this->eventSubscriberSpy === null) {
            $this->eventSubscriberSpy = new EventSubscriberSpy();
        }

        return $this->eventSubscriberSpy;
    }

    /**
     * @return array<object>
     */
    public function dispatchedEvents(): array
    {
        return $this->eventSubscriberSpy()->dispatchedEvents();
    }
}
