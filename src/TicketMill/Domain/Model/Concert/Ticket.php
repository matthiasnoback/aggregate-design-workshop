<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Concert;

use TicketMill\Domain\Model\Common\EmailAddress;

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
