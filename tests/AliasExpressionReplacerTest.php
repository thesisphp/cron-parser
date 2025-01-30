<?php

declare(strict_types=1);

namespace Thesis\Cron;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(AliasExpressionReplacer::class)]
final class AliasExpressionReplacerTest extends TestCase
{
    /**
     * @param non-empty-string $in
     * @param non-empty-string $out
     */
    #[TestWith(['@yearly', '0 0 1 1 *'])]
    #[TestWith(['@annually', '0 0 1 1 *'])]
    #[TestWith(['@monthly', '0 0 1 * *'])]
    #[TestWith(['@weekly', '0 0 * * 0'])]
    #[TestWith(['@daily', '0 0 * * *'])]
    #[TestWith(['@midnight', '0 0 * * *'])]
    #[TestWith(['@hourly', '0 * * * *'])]
    #[TestWith(['@secondly', '* * * * * *'])]
    public function testReplace(string $in, string $out): void
    {
        $normalizer = new AliasExpressionReplacer(['@secondly' => '* * * * * *']);
        self::assertSame($out, $normalizer->replace($in));
    }
}
