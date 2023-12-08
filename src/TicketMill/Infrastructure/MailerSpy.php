<?php
declare(strict_types=1);

namespace TicketMill\Infrastructure;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use TicketMill\Application\Notifications\Mailer;
use TicketMill\Domain\Model\Common\EmailAddress;

final class MailerSpy implements Mailer
{
    /**
     * @var array<string,array<string>>
     */
    private array $emails = [];

    public function sendReservationWasMadeEmail(EmailAddress $emailAddress, int $numberOfSeats): void
    {
        $this->emails[$emailAddress->asString()][] = sprintf(
            '%d seats have been reserved',
            $numberOfSeats
        );
    }

    public function sendReservationWasRejectedEmail(EmailAddress $emailAddress): void
    {
        $this->emails[$emailAddress->asString()][] = 'your reservation was not accepted';
    }

    public function assertEmailSent(string $emailAddress, string $messageContains): void
    {
        Assert::assertArrayHasKey($emailAddress, $this->emails, 'No mails were sent to ' . $emailAddress);
        foreach ($this->emails[$emailAddress] as $emailBody) {
            if (strpos($emailBody, $messageContains) !== false) {
                return;
            }
        }

        throw new ExpectationFailedException('Expected an email containing: ' . $messageContains);
    }
}
