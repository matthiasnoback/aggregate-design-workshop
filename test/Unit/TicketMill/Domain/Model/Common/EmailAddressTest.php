<?php

namespace TicketMill\Domain\Model\Common;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class EmailAddressTest extends TestCase
{
    public function testItRequiresAValidEmailAddressToBeProvided(): void
    {
        $this->expectException(InvalidArgumentException::class);

        EmailAddress::fromString('invalid-email-address');
    }

    public function testItCanBeCreatedFromAStringAndConvertedBackToIt(): void
    {
        $emailAddress = 'test@example.com';

        self::assertEquals(
            $emailAddress,
            EmailAddress::fromString($emailAddress)->asString()
        );
    }
}
