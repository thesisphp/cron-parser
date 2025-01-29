<?php

declare(strict_types=1);

namespace Thesis\Cron;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

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
        self::assertSame($match, self::matchExpression('0 0 * * *', $time));
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
     * @param non-empty-string $cron
     * @param non-empty-string $time
     */
    private static function matchExpression(string $cron, string $time): bool
    {
        $extension = Parser::standard();

        return $extension->parse($cron)->match(new \DateTimeImmutable($time));
    }
}
