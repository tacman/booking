<?php

declare(strict_types=1);

namespace Booking\Tests\Shared\Infrastructure\PhpUnit;

use Booking\Tests\Shared\Infrastructure\PhpUnit\Constraint\ConstraintIsSimilar;
use Booking\Tests\Shared\Infrastructure\PhpUnit\Utils\TestUtils;
use PHPUnit\Framework\TestCase;

abstract class UnitTestCase extends TestCase
{
    public function isSimilar(mixed $expected, mixed $actual): bool
    {
        return TestUtils::isSimilar($expected, $actual);
    }

    public function assertSimilar(mixed $expected, mixed $actual): void
    {
        TestUtils::assertSimilar($expected, $actual);
        $this->addToAssertionCount(1);
    }

    public function similarTo(mixed $value, float $delta = 0.0): ConstraintIsSimilar
    {
        return TestUtils::similarTo($value, $delta);
    }
}
