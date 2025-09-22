<?php

declare(strict_types=1);

namespace TicketMill\Infrastructure;

final class EventSubscriberSpy
{
    /**
     * @var array<object>
     */
    private array $dispatchedEvents = [];

    public function __invoke(object $event): void
    {
        $this->dispatchedEvents[] = $event;
    }

    /**
     * @return array<object>
     */
    public function dispatchedEvents(): array
    {
        return $this->dispatchedEvents;
    }
}
