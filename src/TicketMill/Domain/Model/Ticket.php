<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model;

final class Ticket
{
    /**
     * @var EmailAddress
     */
    private $customerEmailAddress;

    public function __construct(EmailAddress $customerEmailAddress)
    {
        $this->customerEmailAddress = $customerEmailAddress;
    }
}
