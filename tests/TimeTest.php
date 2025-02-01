<?php

declare(strict_types=1);

namespace Thesis\Cron;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(Time::class)]
final class TimeTest extends TestCase
{
    /**
     * @param non-empty-list<\DateTimeImmutable> $times
     */
    #[TestWith([[
        new \DateTimeImmutable('2025-01-29 19:44:01'),
        new \DateTimeImmutable('2025-01-29 19:44:02'),
        new \DateTimeImmutable('2025-01-29 19:44:03'),
        new \DateTimeImmutable('2025-01-29 19:44:04'),
        new \DateTimeImmutable('2025-01-29 19:44:05'),
    ]])]
    public function testEverySecond(array $times): void
    {
        self::assertSequence('* * * * * *', $times);
    }

    /**
     * @param non-empty-list<\DateTimeImmutable> $times
     */
    #[TestWith([[
        new \DateTimeImmutable('2025-01-29 19:45:00'),
        new \DateTimeImmutable('2025-01-29 19:46:00'),
        new \DateTimeImmutable('2025-01-29 19:47:00'),
        new \DateTimeImmutable('2025-01-29 19:48:00'),
        new \DateTimeImmutable('2025-01-29 19:49:00'),
    ]])]
    public function testEveryMinute(array $times): void
    {
        self::assertSequence('* * * * *', $times);
    }

    /**
     * @param non-empty-list<\DateTimeImmutable> $times
     */
    #[TestWith([[
        new \DateTimeImmutable('2025-01-29 20:00:00'),
        new \DateTimeImmutable('2025-01-29 21:00:00'),
        new \DateTimeImmutable('2025-01-29 22:00:00'),
        new \DateTimeImmutable('2025-01-29 23:00:00'),
        new \DateTimeImmutable('2025-01-30 00:00:00'),
    ]])]
    public function testEveryHour(array $times): void
    {
        self::assertSequence('0 * * * *', $times);
    }

    /**
     * @param non-empty-list<\DateTimeImmutable> $times
     */
    #[TestWith([[
        new \DateTimeImmutable('2025-01-30 00:00:00'),
        new \DateTimeImmutable('2025-01-31 00:00:00'),
        new \DateTimeImmutable('2025-02-01 00:00:00'),
        new \DateTimeImmutable('2025-02-02 00:00:00'),
        new \DateTimeImmutable('2025-02-03 00:00:00'),
    ]])]
    public function testEveryDay(array $times): void
    {
        self::assertSequence('0 0 * * *', $times);
    }

    /**
     * @param non-empty-list<\DateTimeImmutable> $times
     */
    #[TestWith([[
        new \DateTimeImmutable('2025-02-01 00:00:00'),
        new \DateTimeImmutable('2025-03-01 00:00:00'),
        new \DateTimeImmutable('2025-04-01 00:00:00'),
        new \DateTimeImmutable('2025-05-01 00:00:00'),
        new \DateTimeImmutable('2025-06-01 00:00:00'),
    ]])]
    public function testEveryMonth(array $times): void
    {
        self::assertSequence('0 0 1 * *', $times);
    }

    /**
     * @param non-empty-list<\DateTimeImmutable> $times
     */
    #[TestWith([[
        new \DateTimeImmutable('2026-01-01 00:00:00'),
        new \DateTimeImmutable('2027-01-01 00:00:00'),
        new \DateTimeImmutable('2028-01-01 00:00:00'),
        new \DateTimeImmutable('2029-01-01 00:00:00'),
        new \DateTimeImmutable('2030-01-01 00:00:00'),
    ]])]
    public function testEveryYear(array $times): void
    {
        self::assertSequence('@yearly', $times);
    }

    /**
     * @param non-empty-list<\DateTimeImmutable> $times
     */
    #[TestWith([[
        new \DateTimeImmutable('2025-02-02 00:00:00'),
        new \DateTimeImmutable('2025-02-09 00:00:00'),
        new \DateTimeImmutable('2025-02-16 00:00:00'),
        new \DateTimeImmutable('2025-02-23 00:00:00'),
        new \DateTimeImmutable('2025-03-02 00:00:00'),
    ]])]
    public function testEverySunday(array $times): void
    {
        self::assertSequence('0 0 * * SUN', $times);
    }

    /**
     * @param non-empty-list<\DateTimeImmutable> $times
     */
    #[TestWith([[
        new \DateTimeImmutable('2025-01-30 00:00:00'),
        new \DateTimeImmutable('2025-01-31 00:00:00'),
        new \DateTimeImmutable('2025-02-03 00:00:00'),
        new \DateTimeImmutable('2025-02-04 00:00:00'),
        new \DateTimeImmutable('2025-02-05 00:00:00'),
        new \DateTimeImmutable('2025-02-06 00:00:00'),
        new \DateTimeImmutable('2025-02-07 00:00:00'),
        new \DateTimeImmutable('2025-02-10 00:00:00'),
    ]])]
    public function testWeekdaysOnly(array $times): void
    {
        self::assertSequence('0 0 * * 1-5', $times);
    }

    /**
     * @param non-empty-list<\DateTimeImmutable> $times
     */
    #[TestWith([[
        new \DateTimeImmutable('2025-02-01 00:00:00'),
        new \DateTimeImmutable('2025-02-02 00:00:00'),
        new \DateTimeImmutable('2025-02-08 00:00:00'),
        new \DateTimeImmutable('2025-02-09 00:00:00'),
        new \DateTimeImmutable('2025-02-15 00:00:00'),
        new \DateTimeImmutable('2025-02-16 00:00:00'),
    ]])]
    public function testEveryWeekend(array $times): void
    {
        self::assertSequence('0 0 * * 6,0', $times);
    }

    /**
     * @param non-empty-list<\DateTimeImmutable> $times
     */
    #[TestWith([[
        new \DateTimeImmutable('2025-01-29 20:00:00'),
        new \DateTimeImmutable('2025-01-29 22:00:00'),
        new \DateTimeImmutable('2025-01-30 00:00:00'),
        new \DateTimeImmutable('2025-01-30 02:00:00'),
        new \DateTimeImmutable('2025-01-30 04:00:00'),
    ]])]
    public function testEvery2Hours(array $times): void
    {
        self::assertSequence('0 */2 * * *', $times);
    }

    /**
     * @param non-empty-list<\DateTimeImmutable> $times
     */
    #[TestWith([[
        new \DateTimeImmutable('2025-01-29 19:44:52'),
        new \DateTimeImmutable('2025-01-29 19:44:54'),
        new \DateTimeImmutable('2025-01-29 19:44:56'),
        new \DateTimeImmutable('2025-01-29 19:44:58'),
        new \DateTimeImmutable('2025-01-29 19:45:00'),
    ]])]
    public function testEvery2Seconds(array $times): void
    {
        self::assertSequence('*/2 * * * * *', $times, new \DateTimeImmutable('2025-01-29 19:44:50'));
    }

    /**
     * @param non-empty-list<\DateTimeImmutable> $times
     */
    #[TestWith([[
        new \DateTimeImmutable('2025-01-29 20:30:00'),
        new \DateTimeImmutable('2025-01-29 21:30:00'),
        new \DateTimeImmutable('2025-01-29 22:30:00'),
        new \DateTimeImmutable('2025-01-29 23:30:00'),
        new \DateTimeImmutable('2025-01-30 00:30:00'),
    ]])]
    public function testEveryHourAt30Minutes(array $times): void
    {
        self::assertSequence('30 * * * *', $times);
    }

    /**
     * @param non-empty-list<\DateTimeImmutable> $times
     */
    #[TestWith([[
        new \DateTimeImmutable('2025-01-29 19:45:00'),
        new \DateTimeImmutable('2025-01-29 20:00:00'),
        new \DateTimeImmutable('2025-01-29 20:15:00'),
        new \DateTimeImmutable('2025-01-29 20:30:00'),
        new \DateTimeImmutable('2025-01-29 20:45:00'),
        new \DateTimeImmutable('2025-01-29 21:00:00'),
    ]])]
    public function testEveryQuarterHour(array $times): void
    {
        self::assertSequence('*/15 * * * *', $times);
    }

    /**
     * @param non-empty-list<\DateTimeImmutable> $times
     */
    #[TestWith([[
        new \DateTimeImmutable('2025-01-29 19:45:00'),
        new \DateTimeImmutable('2025-01-29 19:47:00'),
        new \DateTimeImmutable('2025-01-29 19:49:00'),
        new \DateTimeImmutable('2025-01-29 19:51:00'),
        new \DateTimeImmutable('2025-01-29 19:53:00'),
        new \DateTimeImmutable('2025-01-29 19:55:00'),
    ]])]
    public function testEveryUnevenMinute(array $times): void
    {
        self::assertSequence('1-59/2 * * * *', $times);
    }

    /**
     * @param non-empty-string $cron
     * @param non-empty-list<\DateTimeImmutable> $ticks
     */
    private static function assertSequence(string $cron, array $ticks, \DateTimeImmutable $now = new \DateTimeImmutable('2025-01-29 19:44:00')): void
    {
        $parser = Parser::standard();
        $time = $parser->parse($cron);

        foreach ($ticks as $tick) {
            self::assertEquals($tick, $now = $time->tick($now));
        }
    }
}
