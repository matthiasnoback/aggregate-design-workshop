<?php
declare(strict_types=1);

namespace TicketMill\Domain\Model\Common;

use Assert\Assertion;

trait EventRecording
{
    /**
     * @var array<object>
     */
    private $events = [];

    /**
     * @param object $event
     */
    final private function recordThat($event): void
    {
        Assertion::isObject($event, 'An event should be an object');

        $this->events[] = $event;
    }

    /**
     * @return array<object>
     */
    final public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
