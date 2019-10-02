<?php

namespace TicketMill\Domain\Model;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class EmailAddressTest extends TestCase
{
    /**
     * @test
     */
    public function it_requires_a_valid_email_address_to_be_provided(): void
    {
        $this->expectException(InvalidArgumentException::class);

        EmailAddress::fromString('invalid-email-address');
    }

    /**
     * @test
     */
    public function it_can_be_created_from_a_string_and_converted_back_to_it(): void
    {
        $emailAddress = 'test@example.com';

        self::assertEquals(
            $emailAddress,
            EmailAddress::fromString($emailAddress)->asString()
        );
    }
}
