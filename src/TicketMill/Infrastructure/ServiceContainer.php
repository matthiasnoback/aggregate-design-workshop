<?php
declare(strict_types=1);

namespace TicketMill\Infrastructure;

use Common\EventDispatcher\EventDispatcher;
use TicketMill\Application\CancelReservation;
use TicketMill\Application\ConfirmReservation;
use TicketMill\Application\MakeReservation;
use TicketMill\Application\Notifications\SendMail;
use TicketMill\Application\PlanConcert;
use TicketMill\Application\UpdateAvailableSeats;
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
            $this->eventDispatcher->registerSubscriber(
                ReservationWasConfirmed::class,
                [new SendMail($this->mailer()), 'whenReservationWasConfirmed']
            );
            $this->eventDispatcher->registerSubscriber(
                ReservationWasMade::class,
                [new UpdateAvailableSeats(
                    $this->concertRepository(),
                    $this->eventDispatcher()), 'whenReservationWasMade'
                ]
            );
            $this->eventDispatcher->registerSubscriber(
                ReservationWasAccepted::class,
                [
                    new ConfirmReservation(
                        $this->reservationRepository(),
                        $this->eventDispatcher()
                    ),
                    'whenReservationWasAccepted'
                ]
            );
            $this->eventDispatcher->registerSubscriber(
                ReservationWasCancelled::class,
                [new UpdateAvailableSeats(
                    $this->concertRepository(),
                    $this->eventDispatcher()), 'whenReservationWasCancelled'
                ]
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
}
