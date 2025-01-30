<?php

declare(strict_types=1);

namespace Thesis\Cron;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(SundayExpressionNormalizer::class)]
final class SundayExpressionNormalizerTest extends TestCase
{
    /**
     * @param non-empty-string $in
     */
    #[TestWith([
        '1 * * * 7',
        new Expression('1 * * * 7', '1', '*', '*', '*', '0'),
    ])]
    #[TestWith([
        '1 * * * 0',
        new Expression('1 * * * 0', '1', '*', '*', '*', '0'),
    ])]
    #[TestWith([
        '1 * * * 1',
        new Expression('1 * * * 1', '1', '*', '*', '*', '1'),
    ])]
    public function testNormalize(string $in, Expression $out): void
    {
        $normalizer = new SundayExpressionNormalizer();
        self::assertEquals($out, $normalizer->normalize(Expression::parse($in)));
    }
}
