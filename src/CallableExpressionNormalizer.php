<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 * @phpstan-type NormalizeExpression = callable(non-empty-string): non-empty-string
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

    public function normalize(string $cron): string
    {
        return ($this->normalize)($cron);
    }
}
