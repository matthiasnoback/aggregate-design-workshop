<?php
declare(strict_types=1);

namespace Utility;

use PHPUnit\Framework\ExpectationFailedException;
use stdClass;

final class AggregateTestCaseTest extends AggregateTestCase
{
    /**
     * @test
     */
    public function it_fails_if_the_array_is_empty(): void
    {
        $this->expectException(ExpectationFailedException::class);

        self::assertArrayContainsObjectOfClass(
            Dummy::class,
            []
        );
    }

    /**
     * @test
     */
    public function it_fails_if_the_array_does_not_contain_an_object_of_the_expected_type(): void
    {
        $this->expectException(ExpectationFailedException::class);

        self::assertArrayContainsObjectOfClass(
            Dummy::class,
            [$someOtherTypeOfObject = new stdClass()]
        );
    }

    /**
     * @test
     */
    public function it_succeeds_if_the_array_consists_of_an_object_of_the_expected_type(): void
    {
        self::assertArrayContainsObjectOfClass(
            Dummy::class,
            [new Dummy()]
        );
    }

    /**
     * @test
     */
    public function it_fails_if_the_array_does_not_contain_the_expected_number_of_objects_of_the_given_type(): void
    {
        $this->expectException(ExpectationFailedException::class);

        self::assertArrayContainsObjectOfClass(
            Dummy::class,
            [$oneObject = new Dummy()],
            2
        );
    }
}
