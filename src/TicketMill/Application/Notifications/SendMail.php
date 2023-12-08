<?php
declare(strict_types=1);

namespace TicketMill\Application\Notifications;

use TicketMill\Domain\Model\Reservation\ReservationWasMade;

final class SendMail
{
    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function whenReservationWasMade(ReservationWasMade $reservationWasMade): void
    {
        $this->mailer->sendReservationWasMadeEmail(
            $reservationWasMade->emailAddress(),
            $reservationWasMade->numberOfSeats()
        );
    }
}
