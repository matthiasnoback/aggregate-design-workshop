<?php
declare(strict_types=1);

namespace Utility;

use Assert\Assertion;
use PHPUnit\Framework\Constraint\Constraint;

final class ArrayContainsObjectOfClass extends Constraint
{
    private string $expectedClass;
    private int $expectedNumberOfObjects;

    public function __construct(string $expectedClass, int $expectedNumberOfObjects)
    {
        $this->expectedClass = $expectedClass;
        $this->expectedNumberOfObjects = $expectedNumberOfObjects;
    }

    protected function matches($other): bool
    {
        Assertion::isArray($other);

        $countedNumberOfObjects = 0;

        foreach ($other as $element) {
            if (get_class($element) === $this->expectedClass) {
                $countedNumberOfObjects++;
            }
        }

        if ($countedNumberOfObjects === $this->expectedNumberOfObjects) {
            return true;
        }

        return false;
    }

    public function toString(): string
    {
        return sprintf(
            'contains %d instance(s) of type %s',
            $this->expectedNumberOfObjects,
            $this->expectedClass
        );
    }
}
