<?php

declare(strict_types=1);

namespace Thesis\Cron;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(WeekdayExpressionNormalizer::class)]
final class WeekdayExpressionNormalizerTest extends TestCase
{
    /**
     * @param non-empty-string $in
     */
    #[TestWith([
        '1 * * * MON',
        new Expression('1 * * * MON', '1', '*', '*', '*', '1'),
    ])]
    #[TestWith([
        '* * * * TUE',
        new Expression('* * * * TUE', '*', '*', '*', '*', '2'),
    ])]
    #[TestWith([
        '*/2 * * * WED',
        new Expression('*/2 * * * WED', '*/2', '*', '*', '*', '3'),
    ])]
    #[TestWith([
        '* * * * * THU',
        new Expression('* * * * * THU', '*', '*', '*', '*', '4', '*'),
    ])]
    #[TestWith([
        '1 0 0 1 FRI',
        new Expression('1 0 0 1 FRI', '1', '0', '0', '1', '5'),
    ])]
    #[TestWith([
        '1-2 1-2,5-6 * * SAT',
        new Expression('1-2 1-2,5-6 * * SAT', '1-2', '1-2,5-6', '*', '*', '6'),
    ])]
    #[TestWith([
        '* 1-2 1-2,5-6 * * SUN',
        new Expression('* 1-2 1-2,5-6 * * SUN', '1-2', '1-2,5-6', '*', '*', '0', '*'),
    ])]
    #[TestWith([
        '* * * * 1',
        new Expression('* * * * 1', '*', '*', '*', '*', '1'),
    ])]
    #[TestWith([
        'MON * * * MON',
        new Expression('MON * * * MON', 'MON', '*', '*', '*', '1'),
    ])]
    public function testNormalize(string $in, Expression $out): void
    {
        $normalizer = new WeekdayExpressionNormalizer();
        self::assertEquals($out, $normalizer->normalize(Expression::parse($in)));
    }
}
