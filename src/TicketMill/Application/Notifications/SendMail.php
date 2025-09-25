<?php

declare(strict_types=1);

namespace TicketMill\Application\Notifications;

use TicketMill\Domain\Model\Reservation\ReservationWasMade;

final readonly class SendMail
{
    public function __construct(
        private Mailer $mailer
    ) {
    }

    public function whenReservationWasMade(ReservationWasMade $reservationWasMade): void
    {
        $this->mailer->sendReservationWasMadeEmail(
            $reservationWasMade->emailAddress(),
            $reservationWasMade->numberOfSeats()
        );
    }
}
