<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Common;

use Assert\Assertion;

trait EventRecording
{
    private $events = [];

    final private function recordThat($event): void
    {
        Assertion::isObject($event, 'An event should be an object');

        $this->events[] = $event;
    }

    /**
     * @return array&object[]
     */
    final public function releaseEvents(): array
    {
        return $this->events;
    }
}
