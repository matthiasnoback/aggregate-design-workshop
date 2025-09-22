<?php
declare(strict_types=1);

namespace Utility;

use PHPUnit\Framework\TestCase;

abstract class AggregateTestCase extends TestCase
{
    /**
     * @param array<object> $array
     */
    protected static function assertArrayContainsObjectOfClass(
        string $expectedClass,
        array $array,
        int $expectedNumberOfObjects = 1
    ): void {
        self::assertThat(
            $array,
            new ArrayContainsObjectOfClass($expectedClass, $expectedNumberOfObjects)
        );
    }
}
