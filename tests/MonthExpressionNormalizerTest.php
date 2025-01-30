<?php

declare(strict_types=1);

namespace Thesis\Cron;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(MonthExpressionNormalizer::class)]
final class MonthExpressionNormalizerTest extends TestCase
{
    /**
     * @param non-empty-string $in
     */
    #[TestWith([
        '1 * * JAN *',
        new Expression('1 * * JAN *', '1', '*', '*', '1', '*'),
    ])]
    #[TestWith([
        '1 * * FEB */2',
        new Expression('1 * * FEB */2', '1', '*', '*', '2', '*/2'),
    ])]
    #[TestWith([
        '1 * * MAR 1-2',
        new Expression('1 * * MAR 1-2', '1', '*', '*', '3', '1-2'),
    ])]
    #[TestWith([
        '* 1 * * APR *',
        new Expression('* 1 * * APR *', '1', '*', '*', '4', '*', '*'),
    ])]
    #[TestWith([
        'MAY * * MAY *',
        new Expression('MAY * * MAY *', 'MAY', '*', '*', '5', '*'),
    ])]
    #[TestWith([
        '* * * JUN *',
        new Expression('* * * JUN *', '*', '*', '*', '6', '*'),
    ])]
    #[TestWith([
        '* * * JUL *',
        new Expression('* * * JUL *', '*', '*', '*', '7', '*'),
    ])]
    #[TestWith([
        '* * * AUG *',
        new Expression('* * * AUG *', '*', '*', '*', '8', '*'),
    ])]
    #[TestWith([
        '* * * SEP *',
        new Expression('* * * SEP *', '*', '*', '*', '9', '*'),
    ])]
    #[TestWith([
        '* * * OCT *',
        new Expression('* * * OCT *', '*', '*', '*', '10', '*'),
    ])]
    #[TestWith([
        '* * * NOV *',
        new Expression('* * * NOV *', '*', '*', '*', '11', '*'),
    ])]
    #[TestWith([
        '* * * dec *',
        new Expression('* * * dec *', '*', '*', '*', '12', '*'),
    ])]
    public function testNormalize(string $in, Expression $out): void
    {
        $normalizer = new MonthExpressionNormalizer();
        self::assertEquals($out, $normalizer->normalize(Expression::parse($in)));
    }
}
