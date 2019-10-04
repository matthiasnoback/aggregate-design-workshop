<?php

namespace TicketMill\Infrastructure;

use PHPUnit\Framework\TestCase;
use ReflectionObject;

final class ServiceContainerTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_instantiate_all_publicly_defined_services(): void
    {
        $container = new ServiceContainer();

        $reflection = new ReflectionObject($container);
        foreach ($reflection->getMethods() as $method) {
            if (!$method->isPublic()) {
                continue;
            }

            $method->invoke($container);
            $this->addToAssertionCount(1);
        }
    }
}
