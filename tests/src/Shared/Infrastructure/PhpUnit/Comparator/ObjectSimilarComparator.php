<?php

declare(strict_types=1);

namespace Booking\Tests\Shared\Infrastructure\PhpUnit\Comparator;

use Booking\Shared\Domain\Aggregate\AggregateRoot;
use Booking\Tests\Shared\Infrastructure\PhpUnit\Utils\TestUtils;
use ReflectionObject;
use SebastianBergmann\Comparator\Comparator;
use SebastianBergmann\Comparator\ComparisonFailure;

final class ObjectSimilarComparator extends Comparator
{
    public function accepts($expected, $actual): bool
    {
        return \is_object($expected) && \is_object($actual);
    }

    public function assertEquals($expected, $actual, $delta = 0.0, $canonicalize = false, $ignoreCase = false): void
    {
        $actualEntity = clone $actual;

        if ($actualEntity instanceof AggregateRoot) {
            $actualEntity->pullDomainEvents();
        }

        if (! $this->objectsAreSimilar($expected, $actualEntity)) {
            throw new ComparisonFailure(
                $expected,
                $actual,
                $this->exporter->export($expected),
                $this->exporter->export($actual),
                false,
                'Failed asserting the objects are equal.'
            );
        }
    }

    private function objectsAreSimilar(object $expected, object $actual): bool
    {
        if (! $this->objectsAreTheSameClass($expected, $actual)) {
            return false;
        }

        return $this->objectsPropertiesAreSimilar($expected, $actual);
    }

    private function objectsAreTheSameClass(object $expected, object $actual): bool
    {
        return $expected::class === $actual::class;
    }

    /**
     * @throws \ReflectionException
     */
    private function objectsPropertiesAreSimilar(object $expected, object $actual): bool
    {
        $expectedReflected = new ReflectionObject($expected);
        $actualReflected = new ReflectionObject($actual);

        foreach ($expectedReflected->getProperties() as $expectedReflectedProperty) {
            $actualReflectedProperty = $actualReflected->getProperty($expectedReflectedProperty->getName());

            $expectedReflectedProperty->setAccessible(true);
            $actualReflectedProperty->setAccessible(true);

            $expectedProperty = $expectedReflectedProperty->getValue($expected);
            $actualProperty = $actualReflectedProperty->getValue($actual);

            if (! TestUtils::isSimilar($expectedProperty, $actualProperty)) {
                return false;
            }
        }

        return true;
    }
}
