<?php
declare(strict_types=1);

namespace TicketMill\Infrastructure;

use Assert\Assertion;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use TicketMill\Application\Notifications\Mailer;
use TicketMill\Domain\Model\Common\EmailAddress;

final class MailerSpy implements Mailer
{
    /**
     * @var array
     */
    private $emails = [];

    public function sendReservationWasMadeEmail(EmailAddress $emailAddress, int $numberOfSeats): void
    {
        $this->emails[$emailAddress->asString()][] = sprintf(
            '%d seats have been reserved',
            $numberOfSeats
        );
    }

    public function assertEmailSent(string $emailAddress, string $messageContains): void
    {
        Assert::assertArrayHasKey($emailAddress, $this->emails);
        foreach ($this->emails[$emailAddress] as $emailBody) {
            if (strpos($emailBody, $messageContains) !== false) {
                return;
            }
        }

        throw new ExpectationFailedException('Expected an email containing: ' . $messageContains);
    }
}
