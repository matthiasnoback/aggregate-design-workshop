<?php

namespace TicketMill\Infrastructure;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use TicketMill\Domain\Model\Common\EmailAddress;

final class MailerSpyTest extends TestCase
{
    private MailerSpy $mailer;

    protected function setUp(): void
    {
        $this->mailer = new MailerSpy();
    }

    public function testItFailsIfNoEmailWasSent(): void
    {
        $this->expectException(ExpectationFailedException::class);

        $this->mailer->assertEmailSent('test@example.com', 'body contains');
    }

    public function testItFailsIfAnEmailWasSentButItDoesNotContainTheExpectedMessage(): void
    {
        $emailAddress = $this->anEmailAddress();
        $this->mailer->sendReservationWasMadeEmail($emailAddress, $this->aNumberOfSeats());

        $this->expectException(ExpectationFailedException::class);

        $this->mailer->assertEmailSent($emailAddress->asString(), 'not contained in the body');
    }

    public function testItSucceedsIfAnEmailWasSentAndItContainsTheExpectedMessage(): void
    {
        $emailAddress = $this->anEmailAddress();
        $numberOfSeats = $this->aNumberOfSeats();

        $this->mailer->sendReservationWasMadeEmail($emailAddress, $numberOfSeats);

        $this->mailer->assertEmailSent(
            $emailAddress->asString(),
            $numberOfSeats . ' seats have been reserved'
        );

        // we're happy if it didn't fail
        $this->addToAssertionCount(1);
    }

    private function anEmailAddress(): EmailAddress
    {
        return EmailAddress::fromString('test@example.com');
    }

    private function aNumberOfSeats(): int
    {
        return 10;
    }
}
