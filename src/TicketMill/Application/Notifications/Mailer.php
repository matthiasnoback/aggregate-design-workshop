<?php

namespace TicketMill\Application\Notifications;

use TicketMill\Domain\Model\Common\EmailAddress;

interface Mailer
{
    public function sendReservationWasMadeEmail(EmailAddress $emailAddress, int $numberOfSeats): void;
}
