<?php

declare(strict_types=1);

namespace Utility;

use Assert\Assertion;
use PHPUnit\Framework\Constraint\Constraint;

final class ArrayContainsObjectOfClass extends Constraint
{
    public function __construct(
        private readonly string $expectedClass,
        private readonly int $expectedNumberOfObjects
    ) {
    }

    public function toString(): string
    {
        return sprintf(
            'contains %d instance(s) of type %s',
            $this->expectedNumberOfObjects,
            $this->expectedClass
        );
    }

    #[\Override]
    protected function matches(mixed $other): bool
    {
        Assertion::isArray($other);

        $countedNumberOfObjects = 0;

        foreach ($other as $element) {
            Assertion::isObject($element);
            if ($element::class === $this->expectedClass) {
                $countedNumberOfObjects++;
            }
        }

        if ($countedNumberOfObjects === $this->expectedNumberOfObjects) {
            return true;
        }

        return false;
    }
}
