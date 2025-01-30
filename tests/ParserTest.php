<?php

declare(strict_types=1);

namespace Thesis\Cron;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Thesis\Cron\Exception\CronValueIsOutOfRange;
use Thesis\Cron\Exception\InvalidCronExpression;

/**
 * @see https://crontab.guru/examples.html
 */
#[CoversClass(StandardParserExtension::class)]
#[CoversClass(Parser::class)]
final class ParserTest extends TestCase
{
    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-01-29 13:00:00', true])]
    #[TestWith(['2025-01-29 13:00:01', true])]
    #[TestWith(['2025-01-29 13:00:02', true])]
    #[TestWith(['2025-01-29 13:01:04', true])]
    #[TestWith(['2025-01-29 13:05:00', true])]
    #[TestWith(['2025-01-29 14:06:00', true])]
    public function testEverySecond(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('* * * * * *', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-01-29 13:00:00', true])]
    #[TestWith(['2025-01-29 13:01:00', true])]
    #[TestWith(['2025-01-29 13:03:00', true])]
    #[TestWith(['2025-01-29 13:04:00', true])]
    #[TestWith(['2025-01-29 13:04:01', false])]
    #[TestWith(['2025-01-29 13:04:20', false])]
    #[TestWith(['2025-01-29 13:05:00', true])]
    #[TestWith(['2025-01-29 14:06:00', true])]
    public function testEveryMinute(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('* * * * *', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-01-29 13:00:00', true])]
    #[TestWith(['2025-01-29 14:00:00', true])]
    #[TestWith(['2025-01-29 15:00:00', true])]
    #[TestWith(['2025-01-29 16:00:00', true])]
    #[TestWith(['2025-01-29 16:01:00', false])]
    #[TestWith(['2025-01-29 16:00:01', false])]
    #[TestWith(['2025-01-29 17:00:00', true])]
    #[TestWith(['2025-01-29 18:00:00', true])]
    public function testEveryHour(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('@hourly', $time));
        self::assertSame($match, self::matchExpression('0 * * * *', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-01-29 00:00:00', true])]
    #[TestWith(['2025-01-30 00:00:00', true])]
    #[TestWith(['2025-01-31 00:00:00', true])]
    #[TestWith(['2025-01-31 00:01:00', false])]
    #[TestWith(['2025-01-31 00:00:01', false])]
    #[TestWith(['2025-02-01 00:00:00', true])]
    public function testEveryDay(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('@daily', $time));
        self::assertSame($match, self::matchExpression('@midnight', $time));
        self::assertSame($match, self::matchExpression('0 0 * * *', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-01-26 00:00:00', true])]
    #[TestWith(['2025-02-02 00:00:00', true])]
    #[TestWith(['2025-02-09 00:00:00', true])]
    #[TestWith(['2025-02-23 00:00:00', true])]
    #[TestWith(['2025-02-16 00:01:00', false])]
    #[TestWith(['2025-02-16 00:00:01', false])]
    public function testEveryWeek(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('@weekly', $time));
        self::assertSame($match, self::matchExpression('0 0 * * 0', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-01-01 00:00:00', true])]
    #[TestWith(['2025-02-01 00:00:00', true])]
    #[TestWith(['2025-03-01 00:00:00', true])]
    #[TestWith(['2025-03-01 00:01:00', false])]
    #[TestWith(['2025-03-01 00:00:01', false])]
    #[TestWith(['2025-03-02 00:00:00', false])]
    public function testEveryMonth(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('@monthly', $time));
        self::assertSame($match, self::matchExpression('0 0 1 * *', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-01-01 00:00:00', true])]
    #[TestWith(['2026-01-01 00:00:00', true])]
    #[TestWith(['2027-01-01 00:00:00', true])]
    #[TestWith(['2027-02-01 00:00:00', false])]
    #[TestWith(['2027-01-02 00:00:00', false])]
    #[TestWith(['2027-01-01 01:00:00', false])]
    #[TestWith(['2027-01-01 00:01:00', false])]
    #[TestWith(['2027-01-01 00:00:01', false])]
    public function testEveryYear(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('@yearly', $time));
        self::assertSame($match, self::matchExpression('@annually', $time));
        self::assertSame($match, self::matchExpression('0 0 1 1 *', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-01-29 19:44:00', true])]
    #[TestWith(['2025-01-29 19:44:01', false])]
    #[TestWith(['2025-01-29 19:45:00', false])]
    #[TestWith(['2025-01-29 19:46:00', true])]
    #[TestWith(['2025-01-29 19:47:00', false])]
    #[TestWith(['2025-01-29 19:48:00', true])]
    #[TestWith(['2025-01-29 19:49:00', false])]
    #[TestWith(['2025-01-29 19:50:00', true])]
    public function testEvery2Minute(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('*/2 * * * *', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-01-29 19:49:00', true])]
    #[TestWith(['2025-01-29 19:49:01', false])]
    #[TestWith(['2025-01-29 19:50:00', false])]
    #[TestWith(['2025-01-29 19:51:00', true])]
    #[TestWith(['2025-01-29 19:52:00', false])]
    #[TestWith(['2025-01-29 19:53:00', true])]
    #[TestWith(['2025-01-29 19:54:00', false])]
    #[TestWith(['2025-01-29 19:55:00', true])]
    #[TestWith(['2025-01-29 20:01:00', true])]
    public function testEveryUnevenMinute(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('1-59/2 * * * *', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-01-29 11:00:00', true])]
    #[TestWith(['2025-01-29 11:01:00', false])]
    #[TestWith(['2025-01-29 11:15:00', true])]
    #[TestWith(['2025-01-29 11:16:00', false])]
    #[TestWith(['2025-01-29 11:30:00', true])]
    public function testEveryQuarterHour(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('*/15 * * * *', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-01-29 11:30:00', true])]
    #[TestWith(['2025-01-29 11:31:00', false])]
    #[TestWith(['2025-01-29 12:30:00', true])]
    #[TestWith(['2025-01-29 12:30:01', false])]
    #[TestWith(['2025-01-29 13:30:00', true])]
    public function testEveryHourAt30Minutes(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('30 * * * *', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-01-29 12:00:00', true])]
    #[TestWith(['2025-01-29 13:00:00', false])]
    #[TestWith(['2025-01-29 14:00:00', true])]
    #[TestWith(['2025-01-29 14:01:00', false])]
    #[TestWith(['2025-01-29 16:00:00', true])]
    #[TestWith(['2025-01-29 16:00:01', false])]
    public function testEvery2Hours(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('0 */2 * * *', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-01-29 08:00:00', false])]
    #[TestWith(['2025-01-29 10:00:00', true])]
    #[TestWith(['2025-01-29 11:00:00', true])]
    #[TestWith(['2025-01-29 12:00:00', true])]
    #[TestWith(['2025-01-29 12:01:00', false])]
    #[TestWith(['2025-01-29 13:00:00', true])]
    #[TestWith(['2025-01-29 13:00:01', false])]
    #[TestWith(['2025-01-29 14:00:00', true])]
    #[TestWith(['2025-01-29 18:00:00', false])]
    public function testHourRange(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('0 9-17 * * *', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-02-03 00:00:00', true])]
    #[TestWith(['2025-02-04 00:00:00', false])]
    #[TestWith(['2025-02-10 00:00:00', true])]
    #[TestWith(['2025-02-10 00:01:00', false])]
    #[TestWith(['2025-02-17 00:00:00', true])]
    #[TestWith(['2025-02-17 00:00:01', false])]
    #[TestWith(['2025-02-24 00:00:00', true])]
    public function testEveryMonday(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('0 0 * * MON', $time));
        self::assertSame($match, self::matchExpression('0 0 * * 1', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-02-04 00:00:00', true])]
    #[TestWith(['2025-02-05 00:00:00', false])]
    #[TestWith(['2025-02-11 00:00:00', true])]
    #[TestWith(['2025-02-11 00:01:00', false])]
    #[TestWith(['2025-02-18 00:00:00', true])]
    #[TestWith(['2025-02-18 00:00:01', false])]
    #[TestWith(['2025-02-25 00:00:00', true])]
    public function testEveryTuesday(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('0 0 * * TUE', $time));
        self::assertSame($match, self::matchExpression('0 0 * * 2', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-02-05 00:00:00', true])]
    #[TestWith(['2025-02-06 00:00:00', false])]
    #[TestWith(['2025-02-12 00:00:00', true])]
    #[TestWith(['2025-02-12 00:01:00', false])]
    #[TestWith(['2025-02-19 00:00:00', true])]
    #[TestWith(['2025-02-19 00:00:01', false])]
    #[TestWith(['2025-02-26 00:00:00', true])]
    public function testEveryWednesday(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('0 0 * * WED', $time));
        self::assertSame($match, self::matchExpression('0 0 * * 3', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-02-06 00:00:00', true])]
    #[TestWith(['2025-02-07 00:00:00', false])]
    #[TestWith(['2025-02-13 00:00:00', true])]
    #[TestWith(['2025-02-13 00:01:00', false])]
    #[TestWith(['2025-02-20 00:00:00', true])]
    #[TestWith(['2025-02-20 00:00:01', false])]
    #[TestWith(['2025-02-27 00:00:00', true])]
    public function testEveryThursday(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('0 0 * * THU', $time));
        self::assertSame($match, self::matchExpression('0 0 * * 4', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-02-07 00:00:00', true])]
    #[TestWith(['2025-02-08 00:00:00', false])]
    #[TestWith(['2025-02-14 00:00:00', true])]
    #[TestWith(['2025-02-14 00:01:00', false])]
    #[TestWith(['2025-02-21 00:00:00', true])]
    #[TestWith(['2025-02-21 00:00:01', false])]
    #[TestWith(['2025-02-28 00:00:00', true])]
    public function testEveryFriday(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('0 0 * * FRI', $time));
        self::assertSame($match, self::matchExpression('0 0 * * 5', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-02-08 00:00:00', true])]
    #[TestWith(['2025-02-09 00:00:00', false])]
    #[TestWith(['2025-02-15 00:00:00', true])]
    #[TestWith(['2025-02-15 00:01:00', false])]
    #[TestWith(['2025-02-22 00:00:00', true])]
    #[TestWith(['2025-02-22 00:00:01', false])]
    #[TestWith(['2025-03-01 00:00:00', true])]
    public function testEverySaturday(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('0 0 * * SAT', $time));
        self::assertSame($match, self::matchExpression('0 0 * * 6', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-02-09 00:00:00', true])]
    #[TestWith(['2025-02-10 00:00:00', false])]
    #[TestWith(['2025-02-16 00:00:00', true])]
    #[TestWith(['2025-02-16 00:01:00', false])]
    #[TestWith(['2025-02-23 00:00:00', true])]
    #[TestWith(['2025-02-23 00:00:01', false])]
    #[TestWith(['2025-03-02 00:00:00', true])]
    public function testEverySunday(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('0 0 * * SUN', $time));
        self::assertSame($match, self::matchExpression('0 0 * * 0', $time));
        self::assertSame($match, self::matchExpression('0 0 * * 7', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-01-31 00:00:00', true])]
    #[TestWith(['2025-02-01 00:00:00', false])]
    #[TestWith(['2025-02-02 00:00:00', false])]
    #[TestWith(['2025-02-03 00:00:00', true])]
    #[TestWith(['2025-02-03 01:00:00', false])]
    #[TestWith(['2025-02-04 00:00:00', true])]
    #[TestWith(['2025-02-04 00:10:00', false])]
    #[TestWith(['2025-02-05 00:00:00', true])]
    #[TestWith(['2025-02-05 00:00:10', false])]
    #[TestWith(['2025-02-06 00:00:00', true])]
    #[TestWith(['2025-02-07 00:00:00', true])]
    #[TestWith(['2025-02-08 00:00:00', false])]
    public function testWeekdaysOnly(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('0 0 * * 1-5', $time));
    }

    /**
     * @param non-empty-string $time
     */
    #[TestWith(['2025-02-01 00:00:00', true])]
    #[TestWith(['2025-02-01 01:00:00', false])]
    #[TestWith(['2025-02-02 00:00:00', true])]
    #[TestWith(['2025-02-03 00:00:00', false])]
    #[TestWith(['2025-02-04 00:00:00', false])]
    #[TestWith(['2025-02-05 00:00:00', false])]
    #[TestWith(['2025-02-06 00:00:00', false])]
    #[TestWith(['2025-02-07 00:00:00', false])]
    #[TestWith(['2025-02-08 00:00:00', true])]
    #[TestWith(['2025-02-08 00:01:00', false])]
    #[TestWith(['2025-02-09 00:00:00', true])]
    #[TestWith(['2025-02-09 00:00:01', false])]
    public function testEveryWeekend(string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression('0 0 * * 6,0', $time));
    }

    /**
     * @param non-empty-string $cron
     * @param non-empty-string $time
     */
    #[TestWith(['5 0 * 8 *', '2025-08-01 00:05:00', true])]
    #[TestWith(['5 0 * 8 *', '2025-08-02 00:05:00', true])]
    #[TestWith(['5 0 * 8 *', '2025-08-03 00:05:00', true])]
    #[TestWith(['5 0 * 8 *', '2025-08-03 00:05:01', false])]
    #[TestWith(['5 0 * 8 *', '2025-08-03 01:05:00', false])]
    #[TestWith(['5 0 * 8 *', '2025-08-03 00:06:00', false])]
    #[TestWith(['5 0 * 8 *', '2025-09-03 00:05:00', false])]
    public function testComplex(string $cron, string $time, bool $match): void
    {
        self::assertSame($match, self::matchExpression($cron, $time));
    }

    public function testTooShortCron(): void
    {
        self::expectException(InvalidCronExpression::class);
        Parser::standard()->parse('* * *');
    }

    public function testTooLongCron(): void
    {
        self::expectException(InvalidCronExpression::class);
        Parser::standard()->parse('* * * * * * *');
    }

    public function testNegativeNumbersInCron(): void
    {
        self::expectException(InvalidCronExpression::class);
        self::expectExceptionMessage('Only positive numbers are allowed, but "-1" given.');
        Parser::standard()->parse('-1 * * * *');
    }

    public function testOutOfRange(): void
    {
        self::expectException(CronValueIsOutOfRange::class);
        self::expectExceptionMessage('Number "8" in part "4-8" is out of range.');
        Parser::standard()->parse('* * * * 4-8');
    }

    public function testNotANumber(): void
    {
        self::expectException(InvalidCronExpression::class);
        self::expectExceptionMessage('The cron part value "o" is invalid.');
        Parser::standard()->parse('* * * * o');
    }

    public function testInvalidInterval(): void
    {
        self::expectException(InvalidCronExpression::class);
        self::expectExceptionMessage('Interval "2--2" must contain exactly two values.');
        Parser::standard()->parse('* 2--2 * * *');
    }

    public function testIntervalOverlapped(): void
    {
        self::expectException(InvalidCronExpression::class);
        self::expectExceptionMessage('Right value in interval "3-2" must be greater than left.');
        Parser::standard()->parse('* 3-2 * * *');
    }

    /**
     * @param non-empty-string $cron
     * @param non-empty-string $time
     */
    private static function matchExpression(string $cron, string $time): bool
    {
        $parser = Parser::standard();

        return $parser->parse($cron)->match(new \DateTimeImmutable($time));
    }
}
