<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 * @phpstan-type TimeFormat = 's'|'i'|'H'|'d'|'m'|'w'
 */
final class Expression implements \Stringable
{
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
        private readonly array $seconds,
        private readonly array $minutes,
        private readonly array $hours,
        private readonly array $days,
        private readonly array $months,
        private readonly array $weekdays,
    ) {}

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
     * @param non-empty-list<non-negative-int> $range
     */
    private static function inRange(\DateTimeImmutable $time, string $format, array $range): bool
    {
        return \in_array((int) $time->format($format), $range, strict: true);
    }
}
