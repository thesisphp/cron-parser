<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 * @phpstan-type NormalizeExpression = callable(Expression): Expression
 */
final class CallableExpressionNormalizer implements ExpressionNormalizer
{
    /** @var NormalizeExpression */
    private $normalize;

    /**
     * @param NormalizeExpression $normalize
     */
    public function __construct(
        callable $normalize,
    ) {
        $this->normalize = $normalize;
    }

    public function normalize(Expression $expression): Expression
    {
        return ($this->normalize)($expression);
    }
}
