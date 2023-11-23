<?php

declare(strict_types=1);

namespace Booking\Tests\Shared\Infrastructure\PhpUnit\Utils;

use Booking\Tests\Shared\Infrastructure\PhpUnit\Constraint\ConstraintIsSimilar;

final class TestUtils
{
    public static function isSimilar(mixed $expected, mixed $actual): bool
    {
        return (new ConstraintIsSimilar($expected))->evaluate($actual, '', true);
    }

    public static function assertSimilar(mixed $expected, mixed $actual): void
    {
        $constraint = new ConstraintIsSimilar($expected);

        $constraint->evaluate($actual);
    }

    public static function similarTo(mixed $value, float $delta = 0.0): ConstraintIsSimilar
    {
        return new ConstraintIsSimilar($value, $delta);
    }
}
