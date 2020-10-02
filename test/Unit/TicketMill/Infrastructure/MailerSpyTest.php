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

    /**
     * @test
     */
    public function it_fails_if_no_email_was_sent(): void
    {
        $this->expectException(ExpectationFailedException::class);

        $this->mailer->assertEmailSent('test@example.com', 'body contains');
    }

    /**
     * @test
     */
    public function it_fails_if_an_email_was_sent_but_it_does_not_contain_the_expected_message(): void
    {
        $emailAddress = $this->anEmailAddress();
        $this->mailer->sendReservationWasMadeEmail($emailAddress, $this->aNumberOfSeats());

        $this->expectException(ExpectationFailedException::class);

        $this->mailer->assertEmailSent($emailAddress->asString(), 'not contained in the body');
    }

    /**
     * @test
     */
    public function it_succeeds_if_an_email_was_sent_and_it_contains_the_expected_message(): void
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
