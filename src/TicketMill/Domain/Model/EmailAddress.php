<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model;

use Assert\Assertion;

final class EmailAddress
{
    /**
     * @var string
     */
    private $emailAddress;

    private function __construct(string $emailAddress)
    {
        Assertion::email($emailAddress);
        $this->emailAddress = $emailAddress;
    }

    public static function fromString(string $emailAddress): EmailAddress
    {
        return new self($emailAddress);
    }

    public function asString(): string
    {
        return $this->emailAddress;
    }
}
