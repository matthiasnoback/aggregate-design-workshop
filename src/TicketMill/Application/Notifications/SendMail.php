<?php
declare(strict_types=1);

namespace TicketMill\Application\Notifications;

use TicketMill\Domain\Model\Reservation\ReservationWasConfirmed;

final class SendMail
{
    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function whenReservationWasConfirmed(ReservationWasConfirmed $event): void
    {
        $this->mailer->sendReservationWasConfirmedEmail(
            $event->emailAddress(),
            $event->numberOfSeats()
        );
    }
}
