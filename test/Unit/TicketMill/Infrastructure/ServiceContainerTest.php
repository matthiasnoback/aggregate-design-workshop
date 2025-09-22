<?php

namespace TicketMill\Infrastructure;

use PHPUnit\Framework\TestCase;
use ReflectionObject;

final class ServiceContainerTest extends TestCase
{
    public function testItCanInstantiateAllPubliclyDefinedServices(): void
    {
        $container = new ServiceContainer();

        $reflection = new ReflectionObject($container);
        foreach ($reflection->getMethods() as $method) {
            if (! $method->isPublic()) {
                continue;
            }

            $method->invoke($container);
            $this->addToAssertionCount(1);
        }
    }
}
