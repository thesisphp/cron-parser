<?php

declare(strict_types=1);

namespace Thesis\Cron\Internal;

use Thesis\Cron\Exception\InvalidCronExpression;
use Thesis\Cron\ParserException;

/**
 * @internal
 * @param non-empty-string $value
 * @return non-negative-int
 * @throws ParserException
 */
function assertNumber(string $value): int
{
    if (!is_numeric($value)) {
        throw new InvalidCronExpression(\sprintf('The value in cron "%s" must be numeric.', $value));
    }

    return assertNonNegative((int) $value);
}

/**
 * @internal
 * @return non-empty-string
 * @throws ParserException
 */
function assertNonEmptyString(string $value): string
{
    if ($value === '') {
        throw new InvalidCronExpression('Non empty string expected in the cron.');
    }

    return $value;
}

/**
 * @internal
 * @return non-negative-int
 * @throws ParserException
 */
function assertNonNegative(int $value): int
{
    if ($value < 0) {
        throw new InvalidCronExpression(\sprintf('Only positive numbers are allowed, but "%d" given.', $value));
    }

    return $value;
}
