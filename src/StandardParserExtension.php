<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 */
final class StandardParserExtension
{
    private const SECONDS = Internal\secondsRange;
    private const MINUTES = Internal\minutesRange;
    private const HOURS = Internal\hoursRange;
    private const DAYS = Internal\daysRange;
    private const MONTHS = Internal\monthsRange;
    private const WEEKDAYS = Internal\weekdayRange;

    /**
     * @param non-empty-string $cron
     * @throws ParserException
     */
    public function __invoke(string $cron): Expression
    {
        $parts = preg_split('/\s+/', trim($cron));
        if ($parts === false || \count($parts) < 5 || \count($parts) > 6) {
            throw new Exception\InvalidCronExpression('The cron expression is invalid.');
        }

        [$index, $seconds] = match (\count($parts)) {
            6 => [0, self::values(Internal\assertNonEmptyString($parts[0]), self::SECONDS)],
            default => [-1, [0]],
        };

        return new Expression(
            cron: $cron,
            seconds: $seconds,
            minutes: self::values(Internal\assertNonEmptyString($parts[++$index]), self::MINUTES),
            hours: self::values(Internal\assertNonEmptyString($parts[++$index]), self::HOURS),
            days: self::values(Internal\assertNonEmptyString($parts[++$index]), self::DAYS),
            months: self::values(Internal\assertNonEmptyString($parts[++$index]), self::MONTHS),
            weekdays: self::values(Internal\assertNonEmptyString($parts[++$index]), self::WEEKDAYS),
        );
    }

    /**
     * @param non-empty-string $part
     * @param non-empty-array<non-negative-int, true> $range
     * @return non-empty-list<non-negative-int>
     * @throws ParserException
     */
    private static function values(string $part, array $range): array
    {
        $values = [];

        foreach (explode(',', $part) as $value) {
            $values = array_merge($values, match (true) {
                $value === '*' => array_keys($range),
                is_numeric($value) => [Internal\assertNumber($value)],
                str_contains($value, '/') => [...self::parseStep($value, array_keys($range))],
                str_contains($value, '-') => range(...self::parseInterval($value)),
                default => throw new Exception\InvalidCronExpression(\sprintf('The cron part value "%s" is invalid.', $value)),
            });
        }

        /** @var non-empty-list<non-negative-int> $values */
        $values = array_unique($values);

        foreach ($values as $value) {
            if (!isset($range[$value])) {
                throw new Exception\InvalidCronExpression(\sprintf('Unexpected number "%d" in part "%s".', $value, $part));
            }
        }

        /** @var non-empty-list<non-negative-int> */
        return $values;
    }

    /**
     * @param non-empty-string $interval
     * @return array{non-negative-int, positive-int}
     * @throws ParserException
     */
    private static function parseInterval(string $interval): array
    {
        $parts = explode('-', $interval);
        if (\count($parts) !== 2) {
            throw new Exception\InvalidCronExpression(\sprintf('Interval "%s" must contain exactly two values.', $interval));
        }

        $min = Internal\assertNumber(Internal\assertNonEmptyString($parts[0]));
        $max = Internal\assertNumber(Internal\assertNonEmptyString($parts[1]));

        if ($min > $max) {
            throw new Exception\InvalidCronExpression(\sprintf('Right value in interval "%s" must be greater than left.', $interval));
        }

        /** @var positive-int $max */
        return [$min, $max];
    }

    /**
     * @param non-empty-string $part
     * @param non-empty-list<non-negative-int> $range
     * @return iterable<non-negative-int>
     * @throws ParserException
     */
    private static function parseStep(string $part, array $range): iterable
    {
        $parts = explode('/', $part);
        if (\count($parts) !== 2) {
            throw new Exception\InvalidCronExpression(\sprintf('Step "%s" must contain exactly two values.', $part));
        }

        $range = match (true) {
            $parts[0] === '*' => [$range[0], $range[\count($range) - 1]],
            default => self::parseInterval(Internal\assertNonEmptyString($parts[0])),
        };

        $step = Internal\assertNumber(Internal\assertNonEmptyString($parts[1]));

        for ($i = $range[0]; $i <= $range[1]; $i += $step) {
            yield $i;
        }
    }
}
