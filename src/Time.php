<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 * @template-implements \IteratorAggregate<\DateTimeImmutable>
 */
final class Time implements
    \IteratorAggregate,
    \Stringable,
    \JsonSerializable
{
    private readonly Internal\Range $seconds;

    private readonly Internal\Range $minutes;

    private readonly Internal\Range $hours;

    private readonly Internal\Range $days;

    private readonly Internal\Range $months;

    private readonly Internal\Range $weekdays;

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
        $this->seconds = new Internal\Range($seconds);
        $this->minutes = new Internal\Range($minutes);
        $this->hours = new Internal\Range($hours);
        $this->days = new Internal\Range($days);
        $this->months = new Internal\Range($months);
        $this->weekdays = new Internal\Range($weekdays);
    }

    public function match(\DateTimeImmutable $time): bool
    {
        $timepoint = Internal\Timepoint::fromDateTime($time);

        return $this->seconds->in($timepoint->second)
            && $this->minutes->in($timepoint->minute)
            && $this->hours->in($timepoint->hour)
            && $this->days->in($timepoint->day)
            && $this->months->in($timepoint->month)
            && $this->weekdays->in($timepoint->weekday);
    }

    public function tick(\DateTimeImmutable $time): \DateTimeImmutable
    {
        if ($this->match($time)) {
            $time = $time->modify('+1 second');
        }

        do {
            $time = $this->shift($time);
        } while (!$this->match($time));

        return $time;
    }

    /**
     * @param positive-int $iterations
     * @return \Traversable<\DateTimeImmutable>
     */
    public function iterator(
        \DateTimeImmutable $time = new \DateTimeImmutable('NOW', new \DateTimeZone('UTC')),
        int $iterations = PHP_INT_MAX,
    ): \Traversable {
        while ($iterations-- >= 0) {
            yield $time = $this->tick($time);
        }
    }

    public function getIterator(): \Traversable
    {
        yield from $this->iterator();
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->cron;
    }

    /**
     * @return array{
     *     seconds: non-empty-list<non-negative-int>,
     *     minutes: non-empty-list<non-negative-int>,
     *     hours: non-empty-list<non-negative-int>,
     *     days: non-empty-list<non-negative-int>,
     *     months: non-empty-list<non-negative-int>,
     *     weekdays: non-empty-list<non-negative-int>,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'seconds' => $this->seconds->vector,
            'minutes' => $this->minutes->vector,
            'hours' => $this->hours->vector,
            'days' => $this->days->vector,
            'months' => $this->months->vector,
            'weekdays' => $this->weekdays->vector,
        ];
    }

    private function shift(\DateTimeImmutable $time): \DateTimeImmutable
    {
        $timepoint = Internal\Timepoint::fromDateTime($time);

        return match (true) {
            !$this->months->in($timepoint->month) => $time
                ->setTime(0, 0)
                ->setDate($timepoint->year, $timepoint->month, 1)
                ->modify('+1 month'),
            !($this->days->in($timepoint->day) && $this->weekdays->in($timepoint->weekday)) => $time
                ->setTime(0, 0)
                ->modify('+1 day'),
            !$this->hours->in($timepoint->hour) => $time
                ->setTime($timepoint->hour, 0)
                ->modify('+1 hour'),
            !$this->minutes->in($timepoint->minute) => $time
                ->modify('+1 minute'),
            !$this->seconds->in($timepoint->second) => $time
                ->modify('+1 second'),
            default => $time,
        };
    }
}
