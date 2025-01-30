<?php

declare(strict_types=1);

namespace Thesis\Cron;

use Thesis\Cron\Exception\InvalidCronExpression;

/**
 * @api
 */
final class Expression
{
    /**
     * @param non-empty-string $cron
     * @throws ParserException
     */
    public static function parse(string $cron): self
    {
        /** @var false|non-empty-list<non-empty-string> $fields */
        $fields = preg_split('/\s+/', trim($cron));
        if ($fields === false || \count($fields) < 5 || \count($fields) > 6) {
            throw new InvalidCronExpression('The cron expression is invalid.');
        }

        $index = \count($fields) === 6 ? 0 : -1;

        return new self(
            cron: $cron,
            minutes: $fields[++$index],
            hours: $fields[++$index],
            days: $fields[++$index],
            months: $fields[++$index],
            weekdays: $fields[++$index],
            seconds: \count($fields) === 6 ? $fields[0] : null,
        );
    }

    /**
     * @param non-empty-string $cron
     * @param non-empty-string $minutes
     * @param non-empty-string $hours
     * @param non-empty-string $days
     * @param non-empty-string $months
     * @param non-empty-string $weekdays
     * @param ?non-empty-string $seconds
     */
    public function __construct(
        public readonly string $cron,
        public readonly string $minutes,
        public readonly string $hours,
        public readonly string $days,
        public readonly string $months,
        public readonly string $weekdays,
        public readonly ?string $seconds = null,
    ) {}

    /**
     * @param non-empty-string $minutes
     */
    public function withMinutes(string $minutes): self
    {
        return new self(
            $this->cron,
            $minutes,
            $this->hours,
            $this->days,
            $this->months,
            $this->weekdays,
            $this->seconds,
        );
    }

    /**
     * @param non-empty-string $hours
     */
    public function withHours(string $hours): self
    {
        return new self(
            $this->cron,
            $this->minutes,
            $hours,
            $this->days,
            $this->months,
            $this->weekdays,
            $this->seconds,
        );
    }

    /**
     * @param non-empty-string $days
     */
    public function withDays(string $days): self
    {
        return new self(
            $this->cron,
            $this->minutes,
            $this->hours,
            $days,
            $this->months,
            $this->weekdays,
            $this->seconds,
        );
    }

    /**
     * @param non-empty-string $months
     */
    public function withMonths(string $months): self
    {
        return new self(
            $this->cron,
            $this->minutes,
            $this->hours,
            $this->days,
            $months,
            $this->weekdays,
            $this->seconds,
        );
    }

    /**
     * @param non-empty-string $weekdays
     */
    public function withWeekdays(string $weekdays): self
    {
        return new self(
            $this->cron,
            $this->minutes,
            $this->hours,
            $this->days,
            $this->months,
            $weekdays,
            $this->seconds,
        );
    }

    /**
     * @param ?non-empty-string $seconds
     */
    public function withSeconds(?string $seconds = null): self
    {
        return new self(
            $this->cron,
            $this->minutes,
            $this->hours,
            $this->days,
            $this->months,
            $this->weekdays,
            $seconds,
        );
    }
}
