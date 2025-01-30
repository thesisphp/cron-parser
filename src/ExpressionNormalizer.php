<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 */
interface ExpressionNormalizer
{
    public function normalize(Expression $expression): Expression;
}
