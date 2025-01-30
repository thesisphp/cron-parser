<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 * @phpstan-type TimeFormat = 's'|'i'|'H'|'d'|'m'|'w'
 */
final class Time implements \Stringable
{
    /** @var non-empty-array<non-negative-int, bool> */
    private readonly array $seconds;

    /** @var non-empty-array<non-negative-int, bool> */
    private readonly array $minutes;

    /** @var non-empty-array<non-negative-int, bool> */
    private readonly array $hours;

    /** @var non-empty-array<non-negative-int, bool> */
    private readonly array $days;

    /** @var non-empty-array<non-negative-int, bool> */
    private readonly array $months;

    /** @var non-empty-array<non-negative-int, bool> */
    private readonly array $weekdays;

    /**
     * @param non-empty-string $cron
     * @param non-empty-list<non-negative-int> $seconds
     * @param non-empty-list<non-negative-int> $minutes
     * @param non-empty-list<non-negative-int> $hours
     * @param non-empty-list<non-negative-int> $days
     * @param non-empty-list<non-negative-int> $months
     * @param non-empty-list<non-negative-int> $weekdays
     */
    public function __construct(
        private readonly string $cron,
        array $seconds,
        array $minutes,
        array $hours,
        array $days,
        array $months,
        array $weekdays,
    ) {
        $this->seconds = array_combine($seconds, array_fill(0, \count($seconds), true));
        $this->minutes = array_combine($minutes, array_fill(0, \count($minutes), true));
        $this->hours = array_combine($hours, array_fill(0, \count($hours), true));
        $this->days = array_combine($days, array_fill(0, \count($days), true));
        $this->months = array_combine($months, array_fill(0, \count($months), true));
        $this->weekdays = array_combine($weekdays, array_fill(0, \count($weekdays), true));
    }

    public function match(\DateTimeImmutable $time): bool
    {
        return self::inRange($time, 's', $this->seconds)
            && self::inRange($time, 'i', $this->minutes)
            && self::inRange($time, 'H', $this->hours)
            && self::inRange($time, 'd', $this->days)
            && self::inRange($time, 'm', $this->months)
            && self::inRange($time, 'w', $this->weekdays);
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->cron;
    }

    /**
     * @param TimeFormat $format
     * @param non-empty-array<non-negative-int, bool> $range
     */
    private static function inRange(\DateTimeImmutable $time, string $format, array $range): bool
    {
        return isset($range[(int) $time->format($format)]);
    }
}
