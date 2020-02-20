<?php

namespace TicketMill\Application\Notifications;

use TicketMill\Domain\Model\Common\EmailAddress;

interface Mailer
{
    public function sendReservationWasConfirmedEmail(EmailAddress $emailAddress, int $numberOfSeats): void;
}
