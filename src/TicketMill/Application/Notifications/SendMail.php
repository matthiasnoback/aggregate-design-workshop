<?php
declare(strict_types=1);

namespace TicketMill\Application\Notifications;

use TicketMill\Domain\Model\Reservation\ReservationWasConfirmed;
use TicketMill\Domain\Model\Reservation\ReservationWasMade;

final class SendMail
{
    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function whenReservationWasConfirmed(ReservationWasConfirmed $event): void
    {
        $this->mailer->sendReservationWasMadeEmail(
            $event->emailAddress(),
            $event->numberOfSeats()
        );
    }
}
