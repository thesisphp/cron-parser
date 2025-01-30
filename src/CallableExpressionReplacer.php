<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 * @phpstan-type ReplaceExpression = callable(non-empty-string): non-empty-string
 */
final class CallableExpressionReplacer implements ExpressionReplacer
{
    /** @var ReplaceExpression */
    private $replace;

    /**
     * @param ReplaceExpression $replace
     */
    public function __construct(
        callable $replace,
    ) {
        $this->replace = $replace;
    }

    public function replace(string $cron): string
    {
        return ($this->replace)($cron);
    }
}
