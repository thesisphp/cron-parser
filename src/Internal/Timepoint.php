<?php

declare(strict_types=1);

namespace Thesis\Cron\Internal;

/**
 * @internal
 */
final class Timepoint
{
    public static function fromDateTime(\DateTimeImmutable $time): self
    {
        return new self(
            second: self::second($time),
            minute: self::minute($time),
            hour: self::hour($time),
            day: self::day($time),
            weekday: self::weekday($time),
            month: self::month($time),
            year: self::year($time),
        );
    }

    /**
     * @param non-negative-int $second
     * @param non-negative-int $minute
     * @param non-negative-int $hour
     * @param non-negative-int $day
     * @param non-negative-int $weekday
     * @param non-negative-int $month
     * @param non-negative-int $year
     */
    public function __construct(
        public readonly int $second,
        public readonly int $minute,
        public readonly int $hour,
        public readonly int $day,
        public readonly int $weekday,
        public readonly int $month,
        public readonly int $year,
    ) {}

    /**
     * @return non-negative-int
     */
    private static function second(\DateTimeImmutable $time): int
    {
        return self::extract($time, 's');
    }

    /**
     * @return non-negative-int
     */
    private static function minute(\DateTimeImmutable $time): int
    {
        return self::extract($time, 'i');
    }

    /**
     * @return non-negative-int
     */
    private static function hour(\DateTimeImmutable $time): int
    {
        return self::extract($time, 'G');
    }

    /**
     * @return non-negative-int
     */
    private static function day(\DateTimeImmutable $time): int
    {
        return self::extract($time, 'j');
    }

    /**
     * @return non-negative-int
     */
    private static function month(\DateTimeImmutable $time): int
    {
        return self::extract($time, 'n');
    }

    /**
     * @return non-negative-int
     */
    private static function weekday(\DateTimeImmutable $time): int
    {
        return self::extract($time, 'w');
    }

    /**
     * @return non-negative-int
     */
    private static function year(\DateTimeImmutable $time): int
    {
        return self::extract($time, 'Y');
    }

    /**
     * @param 's'|'i'|'G'|'j'|'n'|'w'|'Y' $format
     * @return non-negative-int
     */
    private static function extract(\DateTimeImmutable $time, string $format): int
    {
        $value = (int) $time->format($format);
        \assert($value >= 0, "time {$value}{$format} < 0");

        return $value;
    }
}
