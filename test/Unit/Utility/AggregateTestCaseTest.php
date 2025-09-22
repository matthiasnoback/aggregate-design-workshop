<?php

declare(strict_types=1);

namespace Utility;

use PHPUnit\Framework\ExpectationFailedException;
use stdClass;

final class AggregateTestCaseTest extends AggregateTestCase
{
    public function testItFailsIfTheArrayIsEmpty(): void
    {
        $this->expectException(ExpectationFailedException::class);

        self::assertArrayContainsObjectOfClass(
            Dummy::class,
            []
        );
    }

    public function testItFailsIfTheArrayDoesNotContainAnObjectOfTheExpectedType(): void
    {
        $this->expectException(ExpectationFailedException::class);

        self::assertArrayContainsObjectOfClass(
            Dummy::class,
            [$someOtherTypeOfObject = new stdClass()]
        );
    }

    public function testItSucceedsIfTheArrayConsistsOfAnObjectOfTheExpectedType(): void
    {
        self::assertArrayContainsObjectOfClass(
            Dummy::class,
            [new Dummy()]
        );
    }

    public function testItFailsIfTheArrayDoesNotContainTheExpectedNumberOfObjectsOfTheGivenType(): void
    {
        $this->expectException(ExpectationFailedException::class);

        self::assertArrayContainsObjectOfClass(
            Dummy::class,
            [$oneObject = new Dummy()],
            2
        );
    }
}
